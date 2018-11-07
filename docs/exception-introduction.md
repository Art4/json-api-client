# Exceptions
[Back to Navigation](README.md)

All Exceptions thrown by JsonApiClient implements `\Art4\JsonApiClient\Exception\Exception` interface. This allows catching all exceptions like this:

```php
use Art4\JsonApiClient\Exception\Exception;

try {
    // do something with JsonApiClient
} catch (Exception $e) {
    // Something went wrong
}
```

## InputException

The `Art4\JsonApiClient\Exception\InputException` will be thrown if the input for `Art4\JsonApiClient\Input\RequestStringInput` or `Art4\JsonApiClient\Input\ResponseStringInput` is not a string or not valid JSON.

```php
use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Input\RequestStringInput;

// If input is not a string
try {
    $input = new RequestStringInput([]); // input must be a string, not array
} catch (InputException $e) {
    echo $e->getMessage(); // "$string must be a string, "array" given."
}

// If input is invalid JSON
$input = new RequestStringInput('This is invalid JSON'); // input must be valid JSON

try {
    $object = $input->getAsObject();
} catch (InputException $e) {
    echo $e->getMessage(); // "Unable to parse JSON data: Syntax error, malformed JSON"
}
```

## ValidationException

The `Art4\JsonApiClient\Exception\ValidationException` will be thrown if the response from the JSON API server doesn't follow the JSON API specification.

```php
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\Parser;

$wrongJsonapi = '{"data":{},"meta":{"info":"This is wrong JSON API. `data` has to be `null` or containing at least `type` and `id`."}}';

try {
    $document = Parser::parseResponseString($wrongJsonapi);
} catch (ValidationException $e) {
    echo $e->getMessage(); // "A resource object MUST contain a type"
}
```

## AccessException

The `Art4\JsonApiClient\Exception\AccessException` will be thrown if you want to `get()` a value that doesn't exists in an object. Use `has()` or `getKeys()` first to check if a value exists.

```php
use Art4\JsonApiClient\Exception\AccessException;

try {
    $value = $resource->get('meta');
} catch (AccessException $e) {
    echo $e->getMessage(); // "meta" doesn't exist in this resource.
}
```
