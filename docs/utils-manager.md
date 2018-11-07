# Utils\Manager
[Back to Navigation](README.md)

**Attention:** `Utils\Manager` is deprecated and will be removed in JsonApiClient 1.0, use [Manager\ErrorAbortManager](manager.md) instead.

The `Utils\Manager` can be used to parse JSON API string and to inject a [Factory](utils-factory.md) for overriding classes.

### Parse a JSON API string

Assuming you have get a response from a JSON API server. Use `parse()` to work with the data.

```php

// The Response body from a JSON API server
$jsonapiString = '{"meta":{"info":"Testing the JsonApiClient library."}}';

$manager = new \Art4\JsonApiClient\Utils\Manager();

$document = $manager->parse($jsonapiString);
```

This returns a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapiString` contains not valid JSON or JSON API a [ValidationException](exception-introduction.md#validationexception) will be thrown.

### Parse a JSON API string for creating a new resource

Assuming you have get a request for creating a new resource. In this case the `id` in the resource item can be missed and you have to tell the Manager about this case.

```php

// The request body from a client
$jsonapiString = '{"data":{"type":"posts","attributes":{"title":"Post Title"}}}';

$manager = new \Art4\JsonApiClient\Utils\Manager();

// Set this to `true`
$manager->setConfig('optional_item_id', true);

$document = $manager->parse($jsonapiString);
```

This returns a [Document](objects-document.md) object which provided all contents.

> **Note:** If `$jsonapiString` contains not valid JSON or JSON API a [ValidationException](exception-introduction.md#validationexception) will be thrown.

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
