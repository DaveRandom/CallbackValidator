<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

abstract class Type
{
    const FLAG_BUILTIN   = 0b00000001 << 8;
    const FLAG_NULLABLE  = 0b00000010 << 8;
    const FLAG_REFERENCE = 0b00000100 << 8;

    const TYPE_STRING   = 'string';
    const TYPE_INT      = 'int';
    const TYPE_FLOAT    = 'float';
    const TYPE_BOOL     = 'bool';
    const TYPE_ARRAY    = 'array';
    const TYPE_VOID     = 'void';
    const TYPE_CALLABLE = 'callable';
    const TYPE_ITERABLE = 'iterable';

    /**
     * Lookup table of non-scalar built-in types
     */
    protected static $nonScalarBuiltInTypes = [
        self::TYPE_ARRAY    => true,
        self::TYPE_CALLABLE => true,
        self::TYPE_VOID     => true,
        self::TYPE_ITERABLE => true,
    ];

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var int
     */
    public $flags;

    protected static function satisfiesIterable($inputTypeName)
    {
        return $inputTypeName === self::TYPE_ITERABLE
            || $inputTypeName === self::TYPE_ARRAY
            || $inputTypeName === \Traversable::class
            || \is_subclass_of($inputTypeName, \Traversable::class);
    }

    protected static function satisfiesCallable($inputTypeName)
    {
        return $inputTypeName === self::TYPE_CALLABLE
            || \method_exists($inputTypeName, '__invoke')
            || $inputTypeName === \Closure::class
            || \is_subclass_of($inputTypeName, \Closure::class);
    }

    /**
     * @param \ReflectionType $inputType
     * @param string $targetTypeName
     * @return bool
     */
    public static function satisfiesBuiltInTypeInWeakMode($inputType, $targetTypeName)
    {
        $inputTypeName = (string)$inputType;

        if ($inputTypeName === $targetTypeName) {
            return true;
        }

        // iterable is a composite type
        if ($targetTypeName === self::TYPE_ITERABLE && self::satisfiesIterable($inputTypeName)) {
            return true;
        }

        // callable is a composite type
        if ($targetTypeName === self::TYPE_CALLABLE && self::satisfiesCallable($inputTypeName)) {
            return true;
        }

        // array, callable, void and iterable can't be cast to anything else
        if (isset(self::$nonScalarBuiltInTypes[$inputTypeName])) {
            return false;
        }

        // Nothing else casts to array, callable, void or iterable
        if (isset(self::$nonScalarBuiltInTypes[$targetTypeName])) {
            return false;
        }

        // Scalars can all cast to each other
        if ($inputType->isBuiltin()) {
            return true;
        }

        // Classes with __toString() satisfy string
        if ($targetTypeName === self::TYPE_STRING && \method_exists($inputTypeName, '__toString')) {
            return true;
        }

        return false;
    }

    /**
     * @param \ReflectionType|null $type
     * @param int $additionalFlags
     */
    protected function __construct($type, $additionalFlags)
    {
        $this->flags = $additionalFlags;

        if ($type === null) {
            return;
        }

        if ($type->isBuiltin()) {
            $this->flags |= self::FLAG_BUILTIN;
        }

        if ($type->allowsNull()) {
            $this->flags |= self::FLAG_NULLABLE;
        }

        $this->name = (string)$type;
    }
}
