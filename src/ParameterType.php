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
     * @param \ReflectionParameter $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate)
    {
        // By-ref must always be the same
        if ($candidate->isPassedByReference() xor ($this->flags & self::FLAG_REFERENCE)) {
            return false;
        }

        // If the candidate has no type, it will satisfy any requirement of the prototype
        if (!$candidate->hasType()) {
            return true;
        }

        $candidateType = $candidate->getType();

        // If the prototype is nullable, the candidate must be as well
        if (($this->flags & self::FLAG_NULLABLE) && !$candidateType->allowsNull()) {
            return false;
        }

        // If the string is an exact match it's definitely a match
        if ($this->name === (string)$candidateType) {
            return true;
        }

        // If a string match didn't pass, built-ins are not possible
        if (($this->flags & self::FLAG_BUILTIN) || $candidateType->isBuiltin()) {
            return false;
        }

        // Parameter types are contravariant
        return \is_subclass_of($this->name, (string)$candidateType);
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
