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
        return new self(
            $reflection->getReturnType(),
            $flags | ($reflection->returnsReference() ? self::FLAG_REFERENCE : 0)
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

        if ($candidateType !== null) {
            $candidateTypeName = (string)$candidateType;
            $candidateTypeNullable = $candidateType->allowsNull();
        } else {
            $candidateTypeName = null;
            $candidateTypeNullable = false;
        }

        $nullable = (bool)($this->flags & self::FLAG_NULLABLE);

        // Candidate is exact match to prototype
        if ($candidateTypeName === $this->name && $candidateTypeNullable === $nullable) {
            return true;
        }

        $strict = (bool)($this->flags & CallbackType::STRICT);

        // Test for a covariant match
        if ($this->flags & CallbackType::RETURN_COVARIANT
            && MatchTester::isVariantMatch($this->name, $nullable, $candidateTypeName, $candidateTypeNullable, $strict)) {
            return true;
        }

        // Test for a contravariant match
        if ($this->flags & CallbackType::RETURN_CONTRAVARIANT
            && MatchTester::isVariantMatch($candidateTypeName, $candidateTypeNullable, $this->name, $nullable, $strict)) {
            return true;
        }

        // No match
        return false;
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
