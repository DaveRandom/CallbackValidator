<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ParameterType extends Type
{
    const FLAG_VARIADIC  = 0b00000001 << 16;
    const FLAG_OPTIONAL  = 0b00000010 << 16;

    /**
     * @var string
     */
    private $paramName;

    /**
     * @param \ReflectionParameter $parameter
     * @param $flags
     * @return ParameterType
     */
    public static function createFromReflectionParameter($parameter, $flags)
    {
        if ($parameter->isVariadic()) {
            $flags |= self::FLAG_VARIADIC;
        }

        if ($parameter->isPassedByReference()) {
            $flags |= self::FLAG_REFERENCE;
        }

        if ($parameter->isOptional()) {
            $flags |= self::FLAG_OPTIONAL;
        }

        return new self($parameter->getName(), $parameter->getType(), $flags);
    }

    /**
     * @param string $paramName
     * @param \ReflectionType|null $type
     * @param int $additionalFlags
     */
    protected function __construct($paramName, $type, $additionalFlags)
    {
        parent::__construct($type, $additionalFlags);
        $this->paramName = $paramName;
    }


    /**
     * @param \ReflectionType|null $candidateType
     * @param bool $candidateReturnsReference
     * @return bool
     */
    public function isSatisfiedBya($candidateType, $candidateReturnsReference)
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

        return false;
    }

    /**
     * @param \ReflectionParameter $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate)
    {
        // By-ref must always be the same
        if ($candidate->isPassedByReference() xor ($this->flags & self::FLAG_REFERENCE)) {
            return false;
        }

        if ($candidate->hasType()) {
            $candidateType = $candidate->getType();
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

        // Test for a contravariant match
        if ($this->flags & CallbackType::PARAMS_CONTRAVARIANT
            && MatchTester::isVariantMatch($candidateTypeName, $candidateTypeNullable, $this->name, $nullable, $strict)) {
            return true;
        }

        // Test for a covariant match
        if ($this->flags & CallbackType::PARAMS_COVARIANT
            && MatchTester::isVariantMatch($this->name, $nullable, $candidateTypeName, $candidateTypeNullable, $strict)) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $string = '';

        if ($this->name !== null) {
            if ($this->flags & self::FLAG_NULLABLE) {
                $string .= '?';
            }

            $string .= $this->name . ' ';
        }

        if ($this->flags & self::FLAG_REFERENCE) {
            $string .= '&';
        }

        if ($this->flags & self::FLAG_VARIADIC) {
            $string .= '...';
        }

        return $string . '$' . $this->paramName;
    }
}
