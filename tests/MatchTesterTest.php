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
    public function test_ClassImplementingTraversable_Match_Iterable_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingTraversable::class, false, false));
    }

    public function test_ClassImplementingTraversable_Match_Iterable_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingTraversable::class, false, true));
    }

    public function test_NullableClassImplementingTraversable_NotMatch_Iterable_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingTraversable::class, true, false));
    }

    public function test_NullableClassImplementingTraversable_NotMatch_Iterable_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingTraversable::class, true, true));
    }

    public function test_ClassImplementingTraversable_Match_NullableIterable_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingTraversable::class, false, false));
    }

    public function test_ClassImplementingTraversable_Match_NullableIterable_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingTraversable::class, false, true));
    }

    public function test_NullableClassImplementingTraversable_Match_NullableIterable_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingTraversable::class, true, false));
    }

    public function test_NullableClassImplementingTraversable_Match_NullableIterable_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingTraversable::class, true, true));
    }

    public function test_Array_Match_Iterable_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::ARRAY, false, false));
    }

    public function test_Array_Match_Iterable_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::ARRAY, false, true));
    }

    public function test_NullableArray_NotMatch_Iterable_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::ARRAY, true, false));
    }

    public function test_NullableArray_NotMatch_Iterable_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::ARRAY, true, true));
    }

    public function test_Array_Match_NullableIterable_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::ARRAY, false, false));
    }

    public function test_Array_Match_NullableIterable_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::ARRAY, false, true));
    }

    public function test_NullableArray_Match_NullableIterable_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::ARRAY, true, false));
    }

    public function test_NullableArray_Match_NullableIterable_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::ARRAY, true, true));
    }

    public function test_ClassNotImplementingTraversable_NotMatch_Iterable_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingNothing::class, false, false));
    }

    public function test_ClassNotImplementingTraversable_NotMatch_Iterable_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingNothing::class, false, true));
    }

    public function test_NullableClassNotImplementingTraversable_NotMatch_Iterable_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingNothing::class, true, false));
    }

    public function test_NullableClassNotImplementingTraversable_NotMatch_Iterable_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, ClassImplementingNothing::class, true, true));
    }

    public function test_ClassNotImplementingTraversable_NotMatch_NullableIterable_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingNothing::class, false, false));
    }

    public function test_ClassNotImplementingTraversable_NotMatch_NullableIterable_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingNothing::class, false, true));
    }

    public function test_NullableClassNotImplementingTraversable_NotMatch_NullableIterable_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingNothing::class, true, false));
    }

    public function test_NullableClassNotImplementingTraversable_NotMatch_NullableIterable_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, ClassImplementingNothing::class, true, true));
    }

    /*
    public function testClassNotImplementingTraversableDoesNotSatisfyIterable()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ITERABLE, ClassImplementingNothing::class));
    }

    public function testClassImplementingInvokeSatisfiesCallable()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::CALLABLE, ClassImplementingInvoke::class));
    }

    public function testClassNotImplementingInvokeDoesNotSatisfyCallable()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::CALLABLE, ClassImplementingNothing::class));
    }

    public function testClassImplementingToStringSatisfiesString()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::STRING, ClassImplementingToString::class));
    }

    public function testClassNotImplementingToStringDoesNotSatisfyString()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::STRING, ClassImplementingNothing::class));
    }

    public function test_NonScalarBuiltInTypesDoNotSatisfyAnyBuiltInTypesTheyShouldnt()
    {
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::STRING, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::STRING, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::STRING, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::STRING, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::INT, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::INT, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::INT, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::INT, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::FLOAT, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::FLOAT, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::FLOAT, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::FLOAT, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::BOOL, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::BOOL, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::BOOL, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::BOOL, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ARRAY, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ARRAY, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ARRAY, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::VOID));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::VOID, BuiltInTypes::ARRAY));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::VOID, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::VOID, BuiltInTypes::ITERABLE));

        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::CALLABLE));
        $this->assertFalse(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::VOID));
    }

    public function test_NonScalarBuiltInTypesSatisfyBuiltInTypesTheyShould()
    {
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ARRAY, BuiltInTypes::ARRAY));
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::CALLABLE, BuiltInTypes::CALLABLE));
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::VOID, BuiltInTypes::VOID));
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::ARRAY));
        $this->assertTrue(MatchTester::satisfiesBuiltInType_WeakMode(BuiltInTypes::ITERABLE, BuiltInTypes::ITERABLE));
    }
    */

    public function test_ScalarSubTypes_Match_ScalarSuperTypes_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::BOOL, false, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::BOOL, false, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::BOOL, false, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::BOOL, false, true));
    }

    public function test_ScalarSubTypes_NotMatch_NonScalarBuiltInSuperTypes_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::BOOL, false, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::BOOL, false, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::BOOL, false, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::BOOL, false, true));
    }

    public function test_ScalarSubTypes_Match_NullableScalarSuperTypes_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::BOOL, false, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::BOOL, false, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::BOOL, false, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::STRING, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::INT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::FLOAT, false, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::BOOL, false, true));
    }

    public function test_ScalarSubTypes_NotMatch_NullableNonScalarBuiltInSuperTypes_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::BOOL, false, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::BOOL, false, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::BOOL, false, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::STRING, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::INT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::FLOAT, false, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::BOOL, false, true));
    }

    public function test_NullableScalarSubTypes_NotMatch_ScalarSuperTypes_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::BOOL, true, true));
    }

    public function test_NullableScalarSubTypes_NotMatch_NonScalarBuiltInSuperTypes_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::BOOL, true, true));
    }

    public function test_NullableScalarSubTypes_Match_NullableScalarSuperTypes_WeakMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::STRING, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::INT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::FLOAT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::BOOL, true, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::STRING, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::INT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::FLOAT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::BOOL, true, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::STRING, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::INT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::FLOAT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::BOOL, true, true));

        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::STRING, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::INT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::FLOAT, true, true));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::BOOL, true, true));
    }

    public function test_NullableScalarSubTypes_NotMatch_NullableNonScalarBuiltInSuperTypes_WeakMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::BOOL, true, true));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::STRING, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::INT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::FLOAT, true, true));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::BOOL, true, true));
    }

    public function test_ScalarSubTypes_InvariantMatch_ScalarSuperTypes_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::STRING, false, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::INT, false, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::FLOAT, false, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::BOOL, false, false));
    }

    public function test_ScalarSubTypes_NotMatch_NonScalarBuiltInSuperTypes_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::BOOL, false, false));
    }

    public function test_ScalarSubTypes_InvariantMatch_NullableScalarSuperTypes_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::STRING, false, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::INT, false, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::FLOAT, false, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::BOOL, false, false));
    }

    public function test_ScalarSubTypes_NotMatch_NullableNonScalarBuiltInSuperTypes_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::BOOL, false, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::STRING, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::INT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::FLOAT, false, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::BOOL, false, false));
    }

    public function test_NullableScalarSubTypes_NotMatch_ScalarSuperTypes_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, false, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, false, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, false, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, false, BuiltInTypes::BOOL, true, false));
    }

    public function test_NullableScalarSubTypes_NotMatch_NonScalarBuiltInSuperTypes_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, false, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, false, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, false, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, false, BuiltInTypes::BOOL, true, false));
    }

    public function test_NullableScalarSubTypes_InvariantMatch_NullableScalarSuperTypes_StrictMode()
    {
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::STRING, true, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::STRING, true, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::INT, true, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::INT, true, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::FLOAT, true, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::FLOAT, true, false));
        $this->assertTrue(MatchTester::isMatch(BuiltInTypes::BOOL, true, BuiltInTypes::BOOL, true, false));
    }

    public function test_NullableScalarSubTypes_NotMatch_NullableNonScalarBuiltInSuperTypes_StrictMode()
    {
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ARRAY, true, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::VOID, true, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::CALLABLE, true, BuiltInTypes::BOOL, true, false));

        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::STRING, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::INT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::FLOAT, true, false));
        $this->assertFalse(MatchTester::isMatch(BuiltInTypes::ITERABLE, true, BuiltInTypes::BOOL, true, false));
    }
}
