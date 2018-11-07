# Helper\Parser
[Back to Navigation](README.md)

The `Art4\JsonApiClient\Helper\Parser` provides some useful methods to deal with JSON API body.

### Parse a JSON API response body

Assuming you have get a response from a JSON API server. Use `parseResponseString()` to work with the data.

```php
use Art4\JsonApiClient\Helper\Parser;

/** @var $response Psr\Http\Message\ResponseInterface */
$jsonapiString = $response->getBody()->getContents();
// $jsonapiString = '{"meta":{"info":"Testing the JsonApiClient library."}}';

$document = Parser::parseResponseString($jsonapiString);
```

`$document` will be a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapiString` contains not valid JSON a [InputException](exception-introduction.md#inputexception) will be thrown.
> **Note:** If `$jsonapiString` contains not valid JSON API a [ValidationException](exception-introduction.md#validationexception) will be thrown.
>
> See more about Exceptions in the [Exception section](exception-introduction.md).

### Parse a JSON API request body

Assuming you have get a request for creating a new resource. In this case the `id` in the resource item is optional and you have to tell the Manager about this case. Use `parseRequestString()` to work with the data.

```php
use Art4\JsonApiClient\Helper\Parser;

/** @var $request Psr\Http\Message\RequestInterface */

// The request body from a client
$jsonapiString = $request->getBody()->getContents();
// $jsonapiString = '{"data":{"type":"posts","attributes":{"title":"Post Title"}}}';

$document = Parser::parseRequestString($jsonapiString);
```

This returns a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapiString` contains not valid JSON a [InputException](exception-introduction.md#inputexception) will be thrown.
> **Note:** If `$jsonapiString` contains not valid JSON API a [ValidationException](exception-introduction.md#validationexception) will be thrown.
>
> See more about Exceptions in the [Exception section](exception-introduction.md).

### Validate a JSON API response body

JsonApiClient can be used as a validator for a response body:

```php
use Art4\JsonApiClient\Helper\Parser;

$wrong_jsonapi = '{"data":{},"meta":{"info":"This is wrong JSON API. `data` has to be `null` or containing at least `type` and `id`."}}';

if (Parser::isValidResponseString($wrong_jsonapi)) {
    echo 'string is valid.';
} else {
    echo 'string is invalid json api!';
}

// echos 'string is invalid json api!'
```

### Validate a JSON API request body

JsonApiClient can also be used as a validator for a request body:

```php
use Art4\JsonApiClient\Helper\Parser;

$wrong_jsonapi = '{"data":{"type":"post","attributes":{"body":"The post body"}}}';

if (Parser::isValidRequestString($wrong_jsonapi)) {
    echo 'string is valid.';
} else {
    echo 'string is invalid json api!';
}

// echos 'string is valid.'
```
