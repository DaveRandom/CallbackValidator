<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator\Test;

use DaveRandom\CallbackValidator\Type;
use PHPUnit\Framework\TestCase;

class TypeTest extends TestCase
{
    public function testNullType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function __construct() {
                parent::__construct(null, 0);
            }
        };

        $this->assertFalse($type->hasType());
        $this->assertSame(null, $type->getType());
        $this->assertFalse($type->isNullable());
        $this->assertFalse($type->isBuiltInType());
        $this->assertFalse($type->isByReference());
    }

    public function testAdditionalFlags()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function __construct() {
                parent::__construct(null, self::REFERENCE);
            }
        };

        $this->assertFalse($type->hasType());
        $this->assertSame(null, $type->getType());
        $this->assertFalse($type->isNullable());
        $this->assertFalse($type->isBuiltInType());
        $this->assertTrue($type->isByReference());
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

        $this->assertTrue($type->hasType());
        $this->assertSame('string', $type->getType());
        $this->assertFalse($type->isNullable());
        $this->assertTrue($type->isBuiltInType());
        $this->assertFalse($type->isByReference());
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

        $this->assertTrue($type->hasType());
        $this->assertSame(Type::class, $type->getType());
        $this->assertFalse($type->isNullable());
        $this->assertFalse($type->isBuiltInType());
        $this->assertFalse($type->isByReference());
    }

    public function testNullableBuiltInType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function method(?string $arg) {}
            public function __construct() {
                parent::__construct((new \ReflectionMethod($this, 'method'))->getParameters()[0]->getType(), 0);
            }
        };

        $this->assertTrue($type->hasType());
        $this->assertSame('string', $type->getType());
        $this->assertTrue($type->isNullable());
        $this->assertTrue($type->isBuiltInType());
        $this->assertFalse($type->isByReference());
    }

    public function testNullableClassType()
    {
        /** @var Type $type */
        $type = new class extends Type {
            public function method(?Type $arg) {}
            public function __construct() {
                parent::__construct((new \ReflectionMethod($this, 'method'))->getParameters()[0]->getType(), 0);
            }
        };

        $this->assertTrue($type->hasType());
        $this->assertSame(Type::class, $type->getType());
        $this->assertTrue($type->isNullable());
        $this->assertFalse($type->isBuiltInType());
        $this->assertFalse($type->isByReference());
    }
}
