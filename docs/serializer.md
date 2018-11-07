# Serializer

JsonApiClient provides Serializer to convert a parsed JSON API document into other formats. Till now JsonApiClient comes only with an `ArraySerializer`.

## ArraySerializer

You can get all data as an array using the `ArraySerializer`.

### Get the containing data as array

```php
use Art4\JsonApiClient\Serializer\ArraySerializer;

$serializer = new ArraySerializer();
$array = $serializer->serialize($document);

var_dump($array);
```

This returns:

```php
array(1) {
  ["meta"] => object(Art4\JsonApiClient\V1\Meta)#9 (2) { ... }
}
```

If you want a full array without any objects, set the `recursive` configuration into the `ArraySerializer` to parse all objects recursively into arrays.

```php
use Art4\JsonApiClient\Serializer\ArraySerializer;

$serializer = new ArraySerializer(['recursive' => true]);
$array = $serializer->serialize($document);

var_dump($array);
```

This returns:

```php
array(1) {
  ["meta"] => array(1) {
    ["info"] => string(28) "Testing the JsonApiClient library."
  }
}
```
