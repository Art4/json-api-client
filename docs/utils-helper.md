## Utils\Helper
[Back to Navigation](README.md)

The `Utils\Helper` provides some useful methods to deal with JSON.

### Parse a JSON API body

Assuming you have get a response from a JSON API server. Use `parse()` to work with the data.

```php

// The Response body from a JSON API server
$jsonapi_string = '{"meta":{"info":"Testing the JSON API Client."}}';

$document = \Art4\JsonApiClient\Utils\Helper::parse($jsonapi_string);
```

This returns a [Document](objects-document.md) object which provided all contents.

### Validate a JSON API response body

JSON API Client can be used as a validator. It will throw an `ValidationException` if the body contains not valid JSON or JSON API.

```php
$wrong_jsonapi = '{"data":{},"meta":{"info":"This is wrong JSON API. `data` has to be `null` or containing at least `type` and `id`."}}';

try
{
	$document = \Art4\JsonApiClient\Utils\Helper::parse($wrong_jsonapi);
}
catch (\Art4\JsonApiClient\Exception\ValidationException $e)
{
	echo $e->getMessage(); // "A resource object MUST contain a type"
}
```

See more about Exceptions in the [Exception section](exception-introduction.md).
