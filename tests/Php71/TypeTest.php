<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator\Test\Php71;

use DaveRandom\CallbackValidator\Type;

class TypeTest extends BasePhp71Test
{
    public function testVoidAndIterableAreNotCastableToAnyOtherBuiltIn()
    {
        $voidType = (new \ReflectionFunction(function():void{}))->getReturnType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_STRING));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_INT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_FLOAT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_BOOL));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_ARRAY));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($voidType, Type::TYPE_ITERABLE));

        /** @noinspection PhpUndefinedClassInspection */
        $iterableType = (new \ReflectionFunction(function(iterable $i){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_STRING));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_INT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_FLOAT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_BOOL));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_ARRAY));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_CALLABLE));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($iterableType, Type::TYPE_ITERABLE));
    }
}
