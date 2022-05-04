# Yet Another Decimal Class

This library implements the BC Math library into both an immutable
and mutable subclass pair of classes named ImmutableDecimal and
Decimal (mutable). The classes provide common methods such as
simple arithmetic, equality and comparison methods as well
as formatting and changes to decimal precision.

## Example Usage

### Immutable decimal arithmetic

```php
$balance = new \YetAnother\ImmutableDecimal(144.52, 2);
$newBalance = $balance->plus(55.95);
$newBalance->printValue(); // "200.02"

if ($newBalance->greaterThanOrEqual(200)) {
    print("New balance is at or over $200.");
}
```

### Mutable decimal arithmetic (with helpers)

```php
$amount = d4('129.9523'); // 4-digit precision (mutable) Decimal
$amount->add(87.5); // addition in place
$amount->printValue(); // "217.4523"

$remainder = $amount->modulus(5.5);
$remainder->printValue(); // "2.9523"
```

