<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator\Test;

use DaveRandom\CallbackValidator\BuiltInTypes;
use DaveRandom\CallbackValidator\Type;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testNullType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public $flags;
            public function __construct() {
                parent::__construct(null, 0);
            }
        };

        $this->assertSame(null, $type->name);
        $this->assertFalse((bool)($type->flags & Type::FLAG_NULLABLE));
        $this->assertFalse((bool)($type->flags & Type::FLAG_BUILTIN));
        $this->assertFalse((bool)($type->flags & Type::FLAG_REFERENCE));
    }

    public function testAdditionalFlags()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public $flags;
            public function __construct() {
                parent::__construct(null, Type::FLAG_REFERENCE);
            }
        };

        $this->assertSame(null, $type->name);
        $this->assertFalse((bool)($type->flags & Type::FLAG_NULLABLE));
        $this->assertFalse((bool)($type->flags & Type::FLAG_BUILTIN));
        $this->assertTrue((bool)($type->flags & Type::FLAG_REFERENCE));
    }

    public function testBuiltInType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function method(string $arg) {}
            public function __construct() {
                parent::__construct((new \ReflectionMethod($this, 'method'))->getParameters()[0]->getType(), 0);
            }
        };

        $this->assertSame(BuiltInTypes::STRING, $type->name);
        $this->assertFalse((bool)($type->flags & Type::FLAG_NULLABLE));
        $this->assertTrue((bool)($type->flags & Type::FLAG_BUILTIN));
        $this->assertFalse((bool)($type->flags & Type::FLAG_REFERENCE));
    }

    public function testClassType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function method(Type $arg) {}
            public function __construct() {
                parent::__construct((new \ReflectionMethod($this, 'method'))->getParameters()[0]->getType(), 0);
            }
        };

        $this->assertSame(Type::class, $type->name);
        $this->assertFalse((bool)($type->flags & Type::FLAG_NULLABLE));
        $this->assertFalse((bool)($type->flags & Type::FLAG_BUILTIN));
        $this->assertFalse((bool)($type->flags & Type::FLAG_REFERENCE));
    }

    public function testNullableBuiltInType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function method(string $arg = null) {}
            public function __construct() {
                parent::__construct((new \ReflectionMethod($this, 'method'))->getParameters()[0]->getType(), 0);
            }
        };

        $this->assertSame(BuiltInTypes::STRING, $type->name);
        $this->assertTrue((bool)($type->flags & Type::FLAG_NULLABLE));
        $this->assertTrue((bool)($type->flags & Type::FLAG_BUILTIN));
        $this->assertFalse((bool)($type->flags & Type::FLAG_REFERENCE));
    }

    public function testNullableClassType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function method(Type $arg = null) {}
            public function __construct() {
                parent::__construct((new \ReflectionMethod($this, 'method'))->getParameters()[0]->getType(), 0);
            }
        };

        $this->assertSame(Type::class, $type->name);
        $this->assertTrue((bool)($type->flags & Type::FLAG_NULLABLE));
        $this->assertFalse((bool)($type->flags & Type::FLAG_BUILTIN));
        $this->assertFalse((bool)($type->flags & Type::FLAG_REFERENCE));
    }
}
