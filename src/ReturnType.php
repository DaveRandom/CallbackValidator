<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ReturnType extends Type
{
    public static function createFromReflectionReflectionFunctionAbstract(\ReflectionFunctionAbstract $reflection): self
    {
        return new self($reflection->getReturnType(), $reflection->returnsReference() ? self::REFERENCE : 0);
    }

    public function isSatisfiedBy(?\ReflectionType $candidateType, bool $candidateReturnsReference)
    {
        // By-ref must always be the same
        if ($candidateReturnsReference xor $this->isByReference()) {
            return false;
        }

        // If the prototype has no type, the candidate will definitely satisfy it
        if (!$this->hasType()) {
            return true;
        }

        // If the prototype specified a return type, the candidate must as well
        if ($candidateType === null) {
            return false;
        }

        // Cannot accept null if the prototype didn't specify it
        if ($candidateType->allowsNull() && !$this->isNullable()) {
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

        // Return types are covariant
        return \is_subclass_of((string)$candidateType, $this->getType());
    }

    public function __toString(): string
    {
        return $this->isNullable()
            ? '?' . $this->getType()
            : $this->getType();
    }
}
