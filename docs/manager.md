# Manager
[Back to Navigation](README.md)

The `Art4\JsonApiClient\Manager` can be used to parse a JSON API input and to inject a [Factory](utils-factory.md) for overriding classes.

The `Art4\JsonApiClient\Manager` needs a `Art4\JsonApiClient\Input\Input` instance for parsing. The Input instance is a normalizer that provides the JSON API as a simple object with public attributes like `\stdClass`.

### Parse a JSON API input

Assuming you have get a response from a JSON API server. Use `parse()` to work with the data.

```php
use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\V1\Factory;

// The Response body from a JSON API server
$jsonapiString = '{"meta":{"info":"Testing the JsonApiClient library."}}';

$manager = new ErrorAbortManager(new Factory());

try {
    // Use this if you have a response after calling a JSON API server
    $input = new ResponseStringInput($jsonapiString);

    $document = $manager->parse($input);
} catch (InputException $e) {
    // $jsonapiString is not valid JSON
} catch (ValidationException $e) {
    // $jsonapiString is not valid JSON API
}
```

This returns a [Document](objects-document.md) object which provided all contents.

### Parse a JSON API string for creating a new resource

Assuming you have get a request for creating a new resource. In this case the `id` in the resource item can be missed and you have to tell the Manager about this case.

```php
use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\V1\Factory;

// The request body from a client
$jsonapiString = '{"data":{"type":"posts","attributes":{"title":"Post Title"}}}';

$manager = new ErrorAbortManager(new Factory());

try {
    // Note that here the *Request*StringInput class is used
    $input = new RequestStringInput($jsonapiString);

    $document = $manager->parse($input);
} catch (InputException $e) {
    // $jsonapiString is not valid JSON
} catch (ValidationException $e) {
    // $jsonapiString is not valid JSON API
}
```

This returns a [Document](objects-document.md) object which provided all contents.

### Working with a factory

You can set a custom [Factory](utils-factory.md) to the manager through the constructor.

```php
use Art4\JsonApiClient\Manager\ErrorAbortManager;

$manager = new ErrorAbortManager(
    new \My\Own\Factory();
);
```

You can call `getFactory()` to get the setted factory.

```php
$factory = $manager->getFactory();
```

Learn more about the [Factory](utils-factory.md).
