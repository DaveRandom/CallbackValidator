<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ParameterType extends Type
{
    private const VARIADIC  = 0b00001000;
    private const OPTIONAL  = 0b00010000;

    private $name;

    public static function createFromReflectionParameter(\ReflectionParameter $parameter): self
    {
        $flags = 0;

        if ($parameter->isVariadic()) {
            $flags |= self::VARIADIC;
        }

        if ($parameter->isPassedByReference()) {
            $flags |= self::REFERENCE;
        }

        if ($parameter->isOptional()) {
            $flags |= self::OPTIONAL;
        }

        return new self($parameter->getName(), $parameter->getType(), $flags);
    }

    protected function __construct(string $name, ?\ReflectionType $type, int $additionalFlags)
    {
        parent::__construct($type, $additionalFlags);
        $this->name = $name;
    }

    public function isVariadic(): bool
    {
        return $this->hasFlag(self::VARIADIC);
    }

    public function isOptional(): bool
    {
        return $this->hasFlag(self::OPTIONAL);
    }

    public function isSatisfiedBy(\ReflectionParameter $candidate): bool
    {
        // By-ref must always be the same
        if ($candidate->isPassedByReference() xor $this->isByReference()) {
            return false;
        }

        // If the candidate has no type, it will satisfy any requirement of the prototype
        if (!$candidate->hasType()) {
            return true;
        }

        $candidateType = $candidate->getType();

        // If the prototype is nullable, the candidate must be as well
        if ($this->isNullable() && !$candidateType->allowsNull()) {
            return false;
        }

        // Built-in type must always be the same
        if ($this->isBuiltInType() xor $candidateType->isBuiltin()) {
            return false;
        }

        // For built-in types, a simple string comparison is all that's required
        if ($this->isBuiltInType()) {
            return $this->getType() === (string)$candidateType;
        }

        // Parameter types are contravariant
        return $this->getType() === (string)$candidateType
            || \is_subclass_of($this->getType(), (string)$candidateType);
    }

    public function __toString(): string
    {
        $string = '';

        if ($this->hasType()) {
            if ($this->isNullable()) {
                $string .= '?';
            }

            $string .= $this->getType() . ' ';
        }

        if ($this->isByReference()) {
            $string .= '&';
        }

        if ($this->isVariadic()) {
            $string .= '...';
        }

        return $string . '$' . $this->name;
    }
}
