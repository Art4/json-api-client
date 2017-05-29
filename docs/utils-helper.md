# Utils\Helper
[Back to Navigation](README.md)

The `Utils\Helper` provides some useful methods to deal with JSON.

### Parse a JSON API body

Assuming you have get a response from a JSON API server. Use `parse()` to work with the data.

```php

// The Response body from a JSON API server
$jsonapi_string = '{"meta":{"info":"Testing the JsonApiClient library."}}';

$document = \Art4\JsonApiClient\Utils\Helper::parse($jsonapi_string);
```

This returns a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapi_string` contains not valid JSON or JSON API a [Exception\ValidationException](exception-introduction.md#exceptionvalidationexception) will be thrown.
>
> See more about Exceptions in the [Exception section](exception-introduction.md).

### Parse a JSON API request body

Assuming you have get a request for creating a new resource. In this case the `id` in the resource item can be missed and you have to tell the Manager about this case. Use `parseRequestBody()` to work with the data.

```php

// The requst body from a client
$jsonapi_string = '{"data":{"type":"posts","attributes":{"title":"Post Title"}}}';

$document = \Art4\JsonApiClient\Utils\Helper::parseRequestBody($jsonapi_string);
```

This returns a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapi_string` contains not valid JSON or JSON API a [Exception\ValidationException](exception-introduction.md#exceptionvalidationexception) will be thrown.
>
> See more about Exceptions in the [Exception section](exception-introduction.md).

### Validate a JSON API response body

JsonApiClient can be used as a validator:

```php
$wrong_jsonapi = '{"data":{},"meta":{"info":"This is wrong JSON API. `data` has to be `null` or containing at least `type` and `id`."}}';

if ( \Art4\JsonApiClient\Utils\Helper::isValid($wrong_jsonapi) )
{
	echo 'string is valid.';
}
else
{
	echo 'string is invalid json api!';
}

// echos 'string is invalid json api!'
```
