<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class CallbackType
{
    /**
     * @var ReturnType
     */
    private $returnType;

    /**
     * @var ParameterType[]
     */
    private $parameters;

    /**
     * Given a callable, create the appropriate reflection
     *
     * This will accept things the PHP would fail to invoke due to scoping, but we can reflect them anyway. Do not add
     * a callable type-hint or this behaviour will break!
     *
     * @param callable $target
     * @return \ReflectionFunction|\ReflectionMethod
     * @throws \ReflectionException
     */
    private static function reflectCallable($target)
    {
        if ($target instanceof \Closure) {
            return new \ReflectionFunction($target);
        }

        if (\is_array($target) && isset($target[0], $target[1])) {
            return new \ReflectionMethod($target[0], $target[1]);
        }

        if (\is_object($target) && \method_exists($target, '__invoke')) {
            return new \ReflectionMethod($target, '__invoke');
        }

        $target = (string)$target;

        return \strpos($target, '::') !== false
            ? new \ReflectionMethod($target)
            : new \ReflectionFunction($target);
    }

    /**
     * @param callable $callable
     * @param int $flags
     * @return CallbackType
     * @throws InvalidCallbackException
     */
    public static function createFromCallable($callable, $flags = ParameterType::CONTRAVARIANT | ReturnType::COVARIANT)
    {
        try {
            $reflection = self::reflectCallable($callable);
        } catch (\ReflectionException $e) {
            throw new InvalidCallbackException('Failed to reflect the supplied callable', 0, $e);
        }

        $returnType = ReturnType::createFromReflectionFunctionAbstract($reflection, $flags);

        $parameters = [];

        foreach ($reflection->getParameters() as $parameterReflection) {
            $parameters[] = ParameterType::createFromReflectionParameter($parameterReflection, $flags);
        }

        return new CallbackType($returnType, ...$parameters);
    }

    public function __construct(ReturnType $returnType, ParameterType ...$parameters)
    {
        $this->returnType = $returnType;
        $this->parameters = $parameters;
    }

    /**
     * @param callable $callable
     * @return bool
     */
    public function isSatisfiedBy($callable)
    {
        try {
            $candidate = self::reflectCallable($callable);
        } catch (\ReflectionException $e) {
            throw new InvalidCallbackException('Failed to reflect the supplied callable', 0, $e);
        }

        $byRef = $candidate->returnsReference();
        $returnType = $candidate->getReturnType();

        if ($returnType !== null) {
            $typeName = (string)$returnType;
            $nullable = $returnType->allowsNull();
        } else {
            $typeName = null;
            $nullable = false;
        }

        if (!$this->returnType->isSatisfiedBy($typeName, $nullable, $byRef)) {
            return false;
        }

        $last = null;

        foreach ($candidate->getParameters() as $position => $parameter) {
            $byRef = $parameter->isPassedByReference();

            if ($parameter->hasType()) {
                $type = $parameter->getType();
                $typeName = (string)$type;
                $nullable = $type->allowsNull();
            } else {
                $typeName = null;
                $nullable = false;
            }

            // Parameters that exist in the prototype must always be satisfied directly
            if (isset($this->parameters[$position])) {
                if (!$this->parameters[$position]->isSatisfiedBy($typeName, $nullable, $byRef)) {
                    return false;
                }

                $last = $this->parameters[$position];
                continue;
            }

            // Candidates can accept additional args that are not in the prototype as long as they are not mandatory
            if (!$parameter->isOptional() && !$parameter->isVariadic()) {
                return false;
            }

            // If the last arg of the prototype is variadic, any additional args the candidate accepts must satisfy it
            if ($last !== null && $last->isVariadic && !$last->isSatisfiedBy($typeName, $nullable, $byRef)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = 'function ';

        if ($this->returnType->isByReference) {
            $string .= '& ';
        }

        $string .= '( ';

        for ($i = $o = 0, $l = count($this->parameters) - 1; $i < $l; $i++) {
            $string .= $this->parameters[$i];

            if (!$o && !($this->parameters[$i + 1]->isOptional)) {
                $string .= ', ';
                continue;
            }

            $string .= ' [, ';
            $o++;
        }

        if (isset($this->parameters[$l])) {
            $string .= $this->parameters[$i] . ' ';
        }

        if ($o) {
            $string .= str_repeat(']', $o) . ' ';
        }

        $string .= ')';

        if ($this->returnType->typeName !== null) {
            $string .= ' : ' . $this->returnType;
        }

        return $string;
    }
}
