<?php declare(strict_types = 1);

namespace DaveRandom\CallbackValidator\Test;

use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingInvoke;
use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingNothing;
use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingToString;
use DaveRandom\CallbackValidator\Test\Fixtures\ClassImplementingTraversable;
use DaveRandom\CallbackValidator\MatchTester;
use DaveRandom\CallbackValidator\BuiltInTypes;
use PHPUnit\Framework\TestCase;

class MatchTesterTest extends TestCase
{
    public function testScalarTypesSatisfyScalarTypes()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::STRING));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::INT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::FLOAT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::BOOL));

        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::STRING));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::INT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::FLOAT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::BOOL));

        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::STRING));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::INT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::FLOAT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::BOOL));

        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::STRING));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::INT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::FLOAT));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::BOOL));
    }

    public function testScalarTypesDoNotSatisfyNonScalarBuiltInTypes()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::STRING));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::INT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::FLOAT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::BOOL));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::STRING));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::INT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::FLOAT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::BOOL));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::STRING));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::INT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::FLOAT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::BOOL));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::STRING));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::INT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::FLOAT));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::BOOL));
    }

    public function testClassImplementingTraversableSatisfiesIterable()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, ClassImplementingTraversable::class));
    }

    public function testClassNotImplementingTraversableDoesNotSatisfyIterable()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, ClassImplementingNothing::class));
    }

    public function testClassImplementingInvokeSatisfiesCallable()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, ClassImplementingInvoke::class));
    }

    public function testClassNotImplementingInvokeDoesNotSatisfyCallable()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, ClassImplementingNothing::class));
    }

    public function testClassImplementingToStringSatisfiesString()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, ClassImplementingToString::class));
    }

    public function testClassNotImplementingToStringDoesNotSatisfyString()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, ClassImplementingNothing::class));
    }

    public function testNonScalarBuiltInTypesDoNotSatisfyAnyBuiltInTypesTheyShouldnt()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::STRING, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::INT, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::FLOAT, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::BOOL, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::VOID));
    }

    public function testNonScalarBuiltInTypesSatisfyBuiltInTypesTheyShould()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ARRAY, BuiltInTypes::ARRAY));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::CALLABLE));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::VOID, BuiltInTypes::VOID));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::ARRAY));
        $this->assertTrue(MatchTester::satisfiesBuiltInTypeInWeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::ITERABLE));
    }
}
