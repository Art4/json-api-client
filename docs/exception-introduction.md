# Exceptions
[Back to Navigation](README.md)

All Exceptions thrown by JSON API client implements `\Art4\JsonApiClient\Exception\Exception` interface. This allows catching all exceptions like this:

```php
try
{
	// do something with JSON API Client
}
catch (\Art4\JsonApiClient\Exception\Exception $e)
{
	// Something went wrong
}
```

## Exception\ValidationException

The `Exception\ValidationException` will be thrown if the response from the JSON API server doesn't follow the JSON API specification.

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
