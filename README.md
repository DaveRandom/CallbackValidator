Callback Validator
==================

Validates callback signatures against a prototype.

## Usage

```php
// Create a prototype function (can be any callable)
$prototype = function (A $a, B $b, $c): ?string {};

// Create a type from the prototype
$type = CallbackType::createFromCallable($prototype);

// Validate that callables match the prototype
$tests = [
    $prototype, // true
    function (A $a, B $b, $c) {}, // false - return type does not match
    function ($a, $b, $c): ?string {}, // true - arguments are contravariant
    function (A $a, B $b): ?string {}, // true - extra args don't cause errors
    function (C $a, B $b, $c): ?string {}, // true if C is a supertype of A, false otherwise
    function (A $a, B $b, $c): string {}, // true, return types are convariant
];

foreach ($tests as $test) {
    if ($type->isSatisfiedBy($test)) {
        echo "pass\n";
    } else {
        // CallbackType implements __toString() for easy inspections
        echo CallbackType::createFromCallable($test) . " does not satisfy $type\n";
    }
}
```

## TODO

Pretty much everything
