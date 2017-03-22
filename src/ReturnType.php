<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ReturnType extends Type
{
    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param int $flags
     * @return ReturnType
     */
    public static function createFromReflectionFunctionAbstract($reflection, $flags)
    {
        $strict = $flags & CallbackType::STRICT;
        $variance = $flags & (CallbackType::RETURN_CONTRAVARIANT | CallbackType::RETURN_COVARIANT | CallbackType::RETURN_INVARIANT);

        // Check that at most one variance flag is set, if none are set then default to covariant
        if ($variance !== ($variance & -$variance)) {
            throw new InvalidCallbackTypeException('More than one return type variance flag was supplied');
        } else if ($flags === 0) {
            $variance = CallbackType::RETURN_COVARIANT;
        }

        return new self(
            $reflection->getReturnType(),
            $strict | $variance | ($reflection->returnsReference() ? self::FLAG_REFERENCE : 0)
        );
    }

    /**
     * @param \ReflectionType|null $candidateType
     * @param bool $candidateReturnsReference
     * @return bool
     */
    public function isSatisfiedBy($candidateType, $candidateReturnsReference)
    {
        // By-ref must always be the same
        if ($candidateReturnsReference xor ($this->flags & self::FLAG_REFERENCE)) {
            return false;
        }

        // If the prototype has no return type, the candidate will definitely satisfy it
        if (!$this->name === null) {
            return true;
        }

        // If the prototype specified a return type, the candidate must as well
        if ($candidateType === null) {
            return false;
        }

        // Cannot accept null if the prototype didn't specify it
        if ($candidateType->allowsNull() && !($this->flags & self::FLAG_BUILTIN)) {
            return false;
        }

        $candidateTypeName = (string)$candidateType;

        // If the string is an exact match it's definitely an invariant match
        if ($this->name === $candidateTypeName) {
            return true;
        }

        // If strict mode is not enabled and it's a built-in type, check whether casting rules can succeed
        if (!($this->flags & CallbackType::STRICT) && ($this->flags & self::FLAG_BUILTIN)) {
            return self::satisfiesBuiltInTypeInWeakMode($candidateType, $this->name);
        }

        // iterable accepts array and Traversable
        if ($this->name === self::TYPE_ITERABLE && self::satisfiesIterable($candidateTypeName)) {
            return true;
        }

        // If a string match didn't pass in strict mode, built-ins and invariance are not possible
        if (($this->flags & (self::FLAG_BUILTIN | CallbackType::RETURN_INVARIANT)) || $candidateType->isBuiltin()) {
            return false;
        }

        return (($this->flags & CallbackType::RETURN_CONTRAVARIANT) && \is_subclass_of($this->name, $candidateTypeName))
            || (($this->flags & CallbackType::RETURN_COVARIANT) && \is_subclass_of($candidateTypeName, $this->name));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return ($this->flags & self::FLAG_NULLABLE)
            ? '?' . $this->name
            : $this->name;
    }
}
