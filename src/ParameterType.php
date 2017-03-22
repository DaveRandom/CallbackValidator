<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ParameterType extends Type
{
    const VARIADIC  = 0b00001000;
    const OPTIONAL  = 0b00010000;

    /**
     * @var string
     */
    private $name;

    /**
     * @param \ReflectionParameter $parameter
     * @return self
     */
    public static function createFromReflectionParameter($parameter)
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

    /**
     * @param string $name
     * @param \ReflectionType|null $type
     * @param int $additionalFlags
     */
    protected function __construct($name, $type, $additionalFlags)
    {
        parent::__construct($type, $additionalFlags);
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isVariadic()
    {
        return $this->hasFlag(self::VARIADIC);
    }

    /**
     * @return bool
     */
    public function isOptional()
    {
        return $this->hasFlag(self::OPTIONAL);
    }

    /**
     * @param \ReflectionParameter $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate)
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

        // If the string is an exact match it's definitely a match
        if ($this->getType() === (string)$candidateType) {
            return true;
        }

        // If a string match didn't pass, built-ins are not possible
        if ($this->isBuiltInType() || $candidateType->isBuiltin()) {
            return false;
        }

        // Parameter types are contravariant
        return \is_subclass_of($this->getType(), (string)$candidateType);
    }

    /**
     * @return string
     */
    public function __toString()
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
