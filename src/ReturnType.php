<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ReturnType extends Type
{
    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @return self
     * @internal
     */
    public static function createFromReflectionReflectionFunctionAbstract($reflection)
    {
        return new self($reflection->getReturnType(), $reflection->returnsReference() ? self::REFERENCE : 0);
    }

    /**
     * @param \ReflectionType|null $candidateType
     * @param bool $candidateReturnsReference
     * @return bool
     * @internal
     */
    public function isSatisfiedBy($candidateType, $candidateReturnsReference)
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

    /**
     * @return string
     * @internal
     */
    public function __toString()
    {
        return $this->isNullable()
            ? '?' . $this->getType()
            : $this->getType();
    }
}
