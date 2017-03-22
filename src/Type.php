<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

abstract class Type
{
    const BUILT_IN  = 0b00000001;
    const NULLABLE  = 0b00000010;
    const REFERENCE = 0b00000100;

    /**
     * @var string|null
     */
    private $type;

    /**
     * @var int
     */
    private $flags;

    /**
     * @param \ReflectionType|null $type
     * @param int $additionalFlags
     * @internal
     */
    protected function __construct($type, $additionalFlags)
    {
        $this->flags = $additionalFlags;

        if ($type === null) {
            return;
        }

        if ($type->isBuiltin()) {
            $this->flags |= self::BUILT_IN;
        }

        if ($type->allowsNull()) {
            $this->flags |= self::NULLABLE;
        }

        $this->type = (string)$type;
    }

    /**
     * @param int $flag
     * @return bool
     * @internal
     */
    protected function hasFlag($flag)
    {
        return (bool)($this->flags & $flag);
    }

    /**
     * @return string|null
     * @internal
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     * @internal
     */
    public function hasType()
    {
        return $this->type !== null;
    }

    /**
     * @return bool
     * @internal
     */
    public function isBuiltInType()
    {
        return (bool)($this->flags & self::BUILT_IN);
    }

    /**
     * @return bool
     * @internal
     */
    public function isByReference()
    {
        return (bool)($this->flags & self::REFERENCE);
    }

    /**
     * @return bool
     * @internal
     */
    public function isNullable()
    {
        return (bool)($this->flags & self::NULLABLE);
    }
}
