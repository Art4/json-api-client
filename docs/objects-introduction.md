# Objects introduction
[Back to Navigation](README.md)

## Object Structure

All possible objects and their hierarchical structure are listed below.

### Symbols

| Symbol | Description |
| ------ | ----------- |
| #      | at least one of these properties is required |
| *      | zero, one or more properties |
| 1      | property is required |
| +      | one or more properties are required |
| ?      | property is optional |
| !      | property is not allowed |

### All objects

1. [Document object](objects-document.md)
1. [Resource Null object](objects-resource-null.md)
1. [Resource Identifier object](objects-resource-identifier.md)
1. [Resource Item object](objects-resource-item.md)
1. [Resource Collection object](objects-resource-collection.md)
1. [Resource Identifier Collection object](objects-resource-identifier-collection.md)
1. [Attributes object](objects-attributes.md)
1. [Relationship Collection object](objects-relationship-collection.md)
1. [Relationship object](objects-relationship.md)
1. [Error Collection object](objects-error-collection.md)
1. [Error object](objects-error.md)
1. [Error Source object](objects-error-source.md)
1. [Link object](objects-link.md)
1. [Document Link object](objects-document-link.md)
1. [Resource Item Link object](objects-resource-item-link.md)
1. [Relationship Link object](objects-relationship-link.md)
1. [Error Link object](objects-error-link.md)
1. [Jsonapi object](objects-jsonapi.md)
1. [Meta object](objects-meta.md)

## Value access

JsonApiClient will parse a JSON API content into a hierarchical object stucture. Every object implements the `Accessable` interface and has these methods for getting the values:

- `has($key)`: Check, if a value exists
- `get($key)`: Get a value
- `getKeys()`: Get the keys of all existing values

### Check if a value exist

You can check for all possible values using the `has()` method.

```php
use Art4\JsonApiClient\Helper\Parser;

$jsonapiString = '{"meta":{"info":"Testing the JsonApiClient library."}}';

$document = Parser::parseResponseString($jsonapiString);

var_dump($document->has('meta'));
```

This returns:

```php
bool(true)
```

The `has()` method has support for dot-notated keys:

```php
var_dump($document->has('meta.info'));
var_dump($document->has('jsonapi.version'));
```

This returns:

```php
bool(true)
bool(false)
```

### Get the keys of all existing values

You can get the keys of all existing values using the `getKeys()` method. Assume we have the same `$document` like in the last example.

```php
var_dump($document->getKeys());
```

This returns:

```php
array(1) {
  0 => 'meta'
}
```

This can be useful to get available values:

```php
foreach($document->getKeys() as $key)
{
    $model->$key = $document->get($key);
}
```

### Get the containing data

You can get all (existing) data using the `get()` method.

```php
$meta = $document->get('meta');

// $meta contains a meta object.
```

> **Note:** Using `get()` on a non-existing value will throw an [AccessException](exception-introduction.md#accessexception). Use `has()` or `getKeys()` to check if a value exists.

The `get()` method has support for dot-notated keys:

```php
var_dump($document->get('meta.info'));
```

This returns:

```php
string(28) "Testing the JsonApiClient library."
```

### Get the containing data as array

You can get all data as an array using the [ArraySerializer](serializer.md#arrayserializer).

### Need more?

If you need more opportunities to get the values take a look at the [Factory](factory.md) to inject more functionality.
