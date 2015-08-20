# Document
[Back to Navigation](README.md)

## Description

The `Document` object represents the [Top Level](http://jsonapi.org/format/#document-top-level) of a JSON API response. You can create it using [Utils\Helper](utils-helper.md).

- extends:
- extended by:
- property of:

### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
1 | data | - [Resource NullResource object](objects-resource-nullresource.md)<br />- [Resource Identifier object](objects-resource-identifier.md)<br />- [Resource Item object](objects-resource-item.md)<br />- [Resource Collection object](objects-resource-collection.md) | not allowed, if 'errors' exists
1 | errors | array([Error object](objects-error.md)) | not allowed, if 'data' exists
1 | meta | [Meta object](objects-meta.md) |
- | jsonapi | [Jsonapi object](objects-jsonapi.md) |
- | links | [Document Link object](objects-document-link.md) |
- | included | array([Resource Item object](objects-resource-item.md)) | not allowed, if 'data' doesn't exist

## Usage

### Check if a value exist

You can check for all possible values using the `has()` method.

```php
$jsonapi_string = '{"meta":{"info":"Testing the JSON API Client."}}';

$document = \Art4\JsonApiClient\Utils\Helper::parse($jsonapi_string);

var_dump($document->has('data'));
var_dump($document->has('errors'));
var_dump($document->has('meta'));
var_dump($document->has('jsonapi'));
var_dump($document->has('links'));
var_dump($document->has('included'));
```

This returns:

```php
false
false
true
false
false
false
```

### Get the keys of all existing values

You can get the keys of all existing values using the `getKeys()` method. Assume we have the same `$document` like in the last example.

```php
var_dump($document->getKeys());
```

This returns:

```php
array(
  0 => 'meta'
)
```

This can be useful to get available values:

```php
foreach($document->getKeys() as $key)
{
	$value = $document->get($key);
}
```

### Get the containing data

You can get all (existing) data using the `get()` method.

```php
$data     = $document->get('data');
$errors   = $document->get('errors');
$meta     = $document->get('meta');
$jsonapi  = $document->get('jsonapi');
$links    = $document->get('links');
$included = $document->get('included');
```

> **Note:** Using `get()` on a non-existing value will throw an `RuntimeException`. Use `has()` or `getKeys()` to check if a value exists.
