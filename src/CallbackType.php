<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class CallbackType
{
    private $returnType;
    private $parameters;

    private static function reflectCallable(callable $callback): \ReflectionFunctionAbstract
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

    public static function createFromCallable(callable $callable): CallbackType
    {
        $reflection = self::reflectCallable($callable);

        return new self(
            ReturnType::createFromReflectionReflectionFunctionAbstract($reflection),
            ...array_map([ParameterType::class, 'createFromReflectionParameter'], $reflection->getParameters())
        );
    }

    private function __construct(ReturnType $returnType, ParameterType ...$parameters)
    {
        $this->returnType = $returnType;
        $this->parameters = $parameters;
    }

    public function isSatisfiedBy(callable $callable): bool
    {
        $candidate = self::reflectCallable($callable);

        if (!$this->returnType->isSatisfiedBy($candidate->getReturnType(), $candidate->returnsReference())) {
            return false;
        }

        $lastParameter = null;

        foreach ($candidate->getParameters() as $position => $parameter) {
            // Parameters that exist in the prototype must always be satisfied directly
            if (isset($this->parameters[$position])) {
                if (!$this->parameters[$position]->isSatisfiedBy($parameter)) {
                    return false;
                }

                $lastParameter = $this->parameters[$position];
                continue;
            }

            // Candidates can accept additional args that are not in the prototype as long as they are not mandatory
            if (!$parameter->isOptional() && !$parameter->isVariadic()) {
                return false;
            }

            // If the last arg of the prototype is variadic, any additional args the candidate accepts must satisfy it
            if ($lastParameter !== null && $lastParameter->isVariadic() && !$lastParameter->isSatisfiedBy($parameter)) {
                return false;
            }
        }

        return true;
    }

    public function __toString(): string
    {
        $string = 'function ';

        if ($this->returnType->isByReference()) {
            $string .= '& ';
        }

        $string .= '( ';

        for ($i = $o = 0, $l = count($this->parameters) - 1; $i < $l; $i++) {
            $string .= $this->parameters[$i];

            if (!$o && !$this->parameters[$i + 1]->isOptional()) {
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

        if ($this->returnType->hasType()) {
            $string .= ' : ' . $this->returnType;
        }

        return $string;
    }
}
