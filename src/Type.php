<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

abstract class Type
{
    protected const BUILT_IN  = 0b0001;
    protected const NULLABLE  = 0b0010;
    protected const REFERENCE = 0b0100;

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
     */
    protected function hasFlag($flag)
    {
        return (bool)($this->flags & $flag);
    }

    /**
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function hasType()
    {
        return $this->type !== null;
    }

    /**
     * @return bool
     */
    public function isBuiltInType()
    {
        return (bool)($this->flags & self::BUILT_IN);
    }

    /**
     * @return bool
     */
    public function isByReference()
    {
        return (bool)($this->flags & self::REFERENCE);
    }

    /**
     * @return bool
     */
    public function isNullable()
    {
        return (bool)($this->flags & self::NULLABLE);
    }
}
