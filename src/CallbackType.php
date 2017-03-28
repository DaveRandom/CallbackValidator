<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class CallbackType
{
    /**
     * Contravariant parameters allow implementors to specify a supertype of that which is specified in the prototype
     */
    const PARAMS_CONTRAVARIANT = 0b00000001;

    /**
     * Covariant parameters allow implementors to specify a subtype of that which is specified in the prototype
     * Usually this isn't a good idea, it's not type-safe, do not use unless you understand what you are doing!
     */
    const PARAMS_COVARIANT     = 0b00000010;

    /**
     * Contravariant return types allow implementors to specify a supertype of that which is specified in the prototype
     * Usually this isn't a good idea, it's not type-safe, do not use unless you understand what you are doing!
     */
    const RETURN_CONTRAVARIANT = 0b00000100;

    /**
     * Covariant return types allow implementors to specify a subtype of that which is specified in the prototype
     */
    const RETURN_COVARIANT     = 0b00001000;

    /**
     * Strict mode validates types using the same rules are strict_types=1
     */
    const STRICT               = 0b00010000;

    /**
     * The default flags allow strictly type-safe variance
     */
    const DEFAULT_FLAGS = self::PARAMS_CONTRAVARIANT | self::RETURN_COVARIANT | self::STRICT;

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
     * @param callable $callback
     * @return \ReflectionFunction|\ReflectionMethod
     * @throws \ReflectionException
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
     * @throws InvalidCallbackTypeException
     */
    public static function createFromCallable($callable, $flags = self::DEFAULT_FLAGS)
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
