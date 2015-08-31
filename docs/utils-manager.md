# Utils\Manager
[Back to Navigation](README.md)

The `Utils\Manager` can be used to parse JSON API string and to inject a [Factory](utils-factory.md) for overriding classes.

### Parse a JSON API string

Assuming you have get a response from a JSON API server. Use `parse()` to work with the data.

```php

// The Response body from a JSON API server
$jsonapi_string = '{"meta":{"info":"Testing the JSON API Client."}}';

$manager = new \Art4\JsonApiClient\Utils\Manager();

$document = $manager->parse($jsonapi_string);
```

This returns a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapi_string` contains not valid JSON or JSON API a [Exception\ValidationException](exception-introduction.md#exceptionvalidationexception) will be thrown.

### Working with a factory

You can set a custom [Factory](utils->factory.md) to the manager through `setFactory()` or the constructor.

```php
$manager = new \Art4\JsonApiClient\Utils\Manager($factory);
// or
$manager->setFactory($factory);
```

With `getFactory()` you can get the setted factory. If you havn't set your own factory you will get the default created factory.

```php
$factory = $manager->getFactory();
```

Learn more about the [Factory](utils-factory.md).
