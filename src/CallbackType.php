<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class CallbackType
{
    const PARAMS_INVARIANT     = 0b00000001;
    const PARAMS_COVARIANT     = 0b00000010;
    const PARAMS_CONTRAVARIANT = 0b00000100;
    const RETURN_INVARIANT     = 0b00001000;
    const RETURN_COVARIANT     = 0b00010000;
    const RETURN_CONTRAVARIANT = 0b00100000;
    const INVARIANT            = 0b00001001;
    const STRICT               = 0b01000000;

    /**
     * @var ReturnType
     */
    private $returnType;

    /**
     * @var ParameterType[]
     */
    private $parameters;

    /**
     * @param callable $callback
     * @return \ReflectionFunction|\ReflectionMethod
     */
    private static function reflectCallable($callback)
    {
        if ($callback instanceof \Closure) {
            return new \ReflectionFunction($callback);
        }

        if (\is_array($callback)) {
            return new \ReflectionMethod($callback[0], $callback[1]);
        }

        if (\is_object($callback)) {
            return new \ReflectionMethod($callback, '__invoke');
        }

        return \strpos($callback, '::') !== false
            ? new \ReflectionMethod($callback)
            : new \ReflectionFunction($callback);
    }

    /**
     * @param ReturnType $returnType
     * @param ParameterType[] $parameters
     */
    private function __construct($returnType, $parameters)
    {
        $this->returnType = $returnType;
        $this->parameters = $parameters;
    }

    /**
     * @param callable $callable
     * @param int $flags
     * @return CallbackType
     */
    public static function createFromCallable($callable, $flags = self::PARAMS_CONTRAVARIANT | self::RETURN_COVARIANT | self::STRICT)
    {
        try {
            $reflection = self::reflectCallable($callable);
        } catch (\ReflectionException $e) {
            throw new InvalidCallbackTypeException('Failed to reflect the supplied callable', 0, $e);
        }

        return new self(
            ReturnType::createFromReflectionFunctionAbstract($reflection, $flags),
            array_map(function($parameter) use($flags) {
                return ParameterType::createFromReflectionParameter($parameter, $flags);
            }, $reflection->getParameters())
        );
    }

    /**
     * @param callable $callable
     * @return bool
     */
    public function isSatisfiedBy($callable)
    {
        $candidate = self::reflectCallable($callable);

        if (!$this->returnType->isSatisfiedBy($candidate->getReturnType(), $candidate->returnsReference())) {
            return false;
        }

        $last = null;

        foreach ($candidate->getParameters() as $position => $parameter) {
            // Parameters that exist in the prototype must always be satisfied directly
            if (isset($this->parameters[$position])) {
                if (!$this->parameters[$position]->isSatisfiedBy($parameter)) {
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
            if ($last !== null && ($last->flags & ParameterType::FLAG_VARIADIC) && !$last->isSatisfiedBy($parameter)) {
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

        if ($this->returnType->flags & Type::FLAG_REFERENCE) {
            $string .= '& ';
        }

        $string .= '( ';

        for ($i = $o = 0, $l = count($this->parameters) - 1; $i < $l; $i++) {
            $string .= $this->parameters[$i];

            if (!$o && !($this->parameters[$i + 1]->flags & ParameterType::FLAG_OPTIONAL)) {
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

        if ($this->returnType->name !== null) {
            $string .= ' : ' . $this->returnType;
        }

        return $string;
    }
}
