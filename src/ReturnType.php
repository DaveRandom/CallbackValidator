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

        // If the prototype has no return type, the candidate will definitely satisfy it
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

        // If the string is an exact match it's definitely a match
        if ($this->getType() === (string)$candidateType) {
            return true;
        }

        // If a string match didn't pass, built-ins are not possible
        if ($this->isBuiltInType() || $candidateType->isBuiltin()) {
            return false;
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
