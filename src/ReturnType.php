<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class ReturnType extends Type
{
    /**
     * Contravariant return types allow implementors to specify a supertype of that which is specified in the prototype
     * Usually this isn't a good idea, it's not type-safe, do not use unless you understand what you are doing!
     */
    const CONTRAVARIANT = 0x01 << 16;

    /**
     * Covariant return types allow implementors to specify a subtype of that which is specified in the prototype
     */
    const COVARIANT = 0x02 << 16;

    /**
     * @param \ReflectionFunctionAbstract $reflection
     * @param int $flags
     * @return ReturnType
     */
    public static function createFromReflectionFunctionAbstract($reflection, $flags = 0)
    {
        if ($reflection->returnsReference()) {
            $flags |= Type::REFERENCE;
        }

        $typeName = null;
        $typeReflection = $reflection->getReturnType();

        if ($typeReflection !== null) {
            $typeName = (string)$typeReflection;

            if ($typeReflection->allowsNull()) {
                $flags |= Type::NULLABLE;
            }
        }

        return new ReturnType($typeName, $flags);
    }

    /**
     * @param string|null $typeName
     * @param int $flags
     */
    public function __construct($typeName, $flags)
    {
        $flags = (int)$flags;

        parent::__construct($typeName, $flags, $flags & self::COVARIANT, $flags & self::CONTRAVARIANT);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->isNullable
            ? '?' . $this->typeName
            : $this->typeName;
    }
}
