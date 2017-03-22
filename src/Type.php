<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator;

abstract class Type
{
    protected const BUILT_IN  = 0b0001;
    protected const NULLABLE  = 0b0010;
    protected const REFERENCE = 0b0100;

    private $type;
    private $flags;

    protected function __construct(?\ReflectionType $type, int $additionalFlags)
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

    protected function hasFlag(int $flag): bool
    {
        return (bool)($this->flags & $flag);
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function hasType(): bool
    {
        return $this->type !== null;
    }

    public function isBuiltInType(): bool
    {
        return (bool)($this->flags & self::BUILT_IN);
    }

    public function isByReference(): bool
    {
        return (bool)($this->flags & self::REFERENCE);
    }

    public function isNullable(): bool
    {
        return (bool)($this->flags & self::NULLABLE);
    }
}
