<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

final class MatchTester
{
    /**
     * Thou shalt not instantiate
     */
    private function __construct() { }

    /**
     * Lookup table of all built-in types
     */
    private static $builtInTypes = [
        BuiltInTypes::STRING   => true,
        BuiltInTypes::INT      => true,
        BuiltInTypes::FLOAT    => true,
        BuiltInTypes::BOOL     => true,
        BuiltInTypes::ARRAY    => true,
        BuiltInTypes::CALLABLE => true,
        BuiltInTypes::VOID     => true,
        BuiltInTypes::ITERABLE => true,
    ];

    /**
     * Lookup table of scalar types
     */
    private static $scalarTypes = [
        BuiltInTypes::STRING => true,
        BuiltInTypes::INT    => true,
        BuiltInTypes::FLOAT  => true,
        BuiltInTypes::BOOL   => true,
    ];

    /**
     * @param string $inputType
     * @return bool
     */
    private static function satisfiesIterable($inputType)
    {
        return $inputType === BuiltInTypes::ITERABLE
            || $inputType === BuiltInTypes::ARRAY
            || $inputType === \Traversable::class
            || \is_subclass_of($inputType, \Traversable::class);
    }

    /**
     * @param string $inputType
     * @return bool
     */
    private static function satisfiesCallable($inputType)
    {
        return $inputType === BuiltInTypes::CALLABLE
            || $inputType === \Closure::class
            || \method_exists($inputType, '__invoke')
            || \is_subclass_of($inputType, \Closure::class);
    }

    private static function isScalarCastable($targetType, $inputType)
    {
        // Nothing else casts to array, callable, void or iterable
        if (!isset(self::$scalarTypes[$targetType])) {
            return false;
        }

        // Scalars can all cast to each other
        if (isset(self::$scalarTypes[$inputType])) {
            return true;
        }

        // Classes with __toString() satisfy string
        if ($targetType === BuiltInTypes::STRING && \method_exists($inputType, '__toString')) {
            return true;
        }

        return false;
    }

    /**
     * @param string $inputType
     * @param string $targetType
     * @return bool
     */
    public static function satisfiesBuiltInTypeInWeakMode($targetType, $inputType)
    {
        $inputType = (string)$inputType;
        $targetType = (string)$targetType;

        // Target must be built in
        if (!isset(self::$builtInTypes[$targetType])) {
            return false;
        }

        // Exact string match is always acceptable
        if ($inputType === $targetType) {
            return true;
        }

        // Check iterable
        if ($targetType === BuiltInTypes::ITERABLE) {
            return self::satisfiesIterable($inputType);
        }

        // Check callable
        if ($targetType === BuiltInTypes::CALLABLE) {
            return self::satisfiesCallable($inputType);
        }

        return self::isScalarCastable($targetType, $inputType);
    }

    /**
     * @param string|null $superTypeName
     * @param bool $superTypeNullable
     * @param string|null $subTypeName
     * @param bool $subTypeNullable
     * @param bool $strict
     * @return bool
     */
    public static function isVariantMatch($superTypeName, $superTypeNullable, $subTypeName, $subTypeNullable, $strict)
    {
        // If the super type is unspecified, anything is a match
        if ($superTypeName === null) {
            return true;
        }

        // If the sub type is unspecified, nothing is a match
        if ($subTypeName === null) {
            return false;
        }

        $superTypeName = (string)$superTypeName;
        $subTypeName = (string)$subTypeName;

        // Sub type cannot be nullable unless the super type is as well
        if ($subTypeNullable && !$superTypeNullable) {
            return false;
        }

        // If the string is an exact match it's definitely acceptable
        if ($superTypeName === $subTypeName) {
            return true;
        }

        // Check iterable
        if ($superTypeName === BuiltInTypes::ITERABLE) {
            return self::satisfiesIterable($subTypeName);
        }

        // Check callable
        if ($superTypeName === BuiltInTypes::CALLABLE) {
            return self::satisfiesCallable($subTypeName);
        }

        // If the super type is built-in, check whether casting rules can succeed, or fail immediately in strict mode
        if (isset(self::$builtInTypes[$superTypeName])) {
            return !$strict
                ? self::isScalarCastable($superTypeName, $subTypeName)
                : false;
        }

        // We now know the super type is not built-in and there's no string match, so only a subclass can satisfy
        return !isset(self::$builtInTypes[$subTypeName])
            ? \is_subclass_of($subTypeName, $superTypeName)
            : false;
    }
}
