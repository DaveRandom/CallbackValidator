<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator\Test;

use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingInvoke;
use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingNothing;
use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingToString;
use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingTraversable;
use DaveRandom\CallbackValidator\Type;
use PHPUnit\Framework\TestCase;

class WeakModeCastsTest extends TestCase
{
    public function testScalarTypesSatisfyScalarTypes()
    {
        $stringType = (new \ReflectionFunction(function(string $s){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_STRING));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_INT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_FLOAT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_BOOL));

        $intType = (new \ReflectionFunction(function(int $i){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_STRING));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_INT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_FLOAT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_BOOL));

        $floatType = (new \ReflectionFunction(function(float $f){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_STRING));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_INT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_FLOAT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_BOOL));

        $boolType = (new \ReflectionFunction(function(bool $b){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_STRING));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_INT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_FLOAT));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_BOOL));
    }

    public function testScalarTypesDoNotSatisfyNonScalarBuiltInTypes()
    {
        $stringType = (new \ReflectionFunction(function(string $s){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_ARRAY));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($stringType, Type::TYPE_ITERABLE));

        $intType = (new \ReflectionFunction(function(int $i){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_ARRAY));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($intType, Type::TYPE_ITERABLE));

        $floatType = (new \ReflectionFunction(function(float $f){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_ARRAY));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($floatType, Type::TYPE_ITERABLE));

        $boolType = (new \ReflectionFunction(function(bool $b){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_ARRAY));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($boolType, Type::TYPE_ITERABLE));
    }

    public function testArraySatisfiesIterable()
    {
        $type = (new \ReflectionFunction(function(array $c){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_ITERABLE));
    }

    public function testClassImplementingTraversableSatisfiesIterable()
    {
        $type = (new \ReflectionFunction(function(ClassImplementingTraversable $c){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_ITERABLE));
    }

    public function testClassNotImplementingTraversableDoesNotSatisfyIterable()
    {
        $type = (new \ReflectionFunction(function(ClassImplementingNothing $c){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_ITERABLE));
    }

    public function testClassImplementingInvokeSatisfiesCallable()
    {
        $type = (new \ReflectionFunction(function(ClassImplementingInvoke $c){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_CALLABLE));
    }

    public function testClassNotImplementingInvokeDoesNotSatisfyCallable()
    {
        $type = (new \ReflectionFunction(function(ClassImplementingNothing $c){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_CALLABLE));
    }

    public function testClassImplementingToStringSatisfiesString()
    {
        $type = (new \ReflectionFunction(function(ClassImplementingToString $c){}))->getParameters()[0]->getType();
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_STRING));
    }

    public function testClassNotImplementingToStringDoesNotSatisfyString()
    {
        $type = (new \ReflectionFunction(function(ClassImplementingNothing $c){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($type, Type::TYPE_STRING));
    }

    public function testNonScalarBuiltInTypesDoNotSatisfyAnyOtherBuiltInType()
    {
        $arrayType = (new \ReflectionFunction(function(array $a){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_STRING));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_INT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_FLOAT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_BOOL));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_ARRAY));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($arrayType, Type::TYPE_VOID));
        // array *does* satisfy to iterable!

        $callableType = (new \ReflectionFunction(function(callable $c){}))->getParameters()[0]->getType();
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_STRING));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_INT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_FLOAT));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_BOOL));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_ARRAY));
        $this->assertTrue(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_CALLABLE));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_VOID));
        $this->assertFalse(Type::satisfiesBuiltInTypeInWeakMode($callableType, Type::TYPE_ITERABLE));
    }
}
