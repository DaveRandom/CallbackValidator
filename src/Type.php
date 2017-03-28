<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

abstract class Type
{
    const FLAG_BUILTIN   = 0b00000001 << 8;
    const FLAG_NULLABLE  = 0b00000010 << 8;
    const FLAG_REFERENCE = 0b00000100 << 8;

    /**
     * @var string|null
     */
    public $name;

    /**
     * @var int
     */
    public $flags;

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
