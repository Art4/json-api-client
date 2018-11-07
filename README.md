# JsonApiClient

[![Latest Version](https://img.shields.io/github/release/Art4/json-api-client.svg)](https://github.com/Art4/json-api-client/releases)
[![Software License](https://img.shields.io/badge/license-GPL3-brightgreen.svg)](LICENSE)
[![Build Status](https://travis-ci.org/Art4/json-api-client.svg?branch=master)](https://travis-ci.org/Art4/json-api-client)
[![Coverage Status](https://coveralls.io/repos/Art4/json-api-client/badge.svg?branch=master&service=github)](https://coveralls.io/github/Art4/json-api-client?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/art4/json-api-client.svg)](https://packagist.org/packages/art4/json-api-client)

JsonApiClient is a PHP Library to validate and handle the response body from a [JSON API](http://jsonapi.org) Server.

Format: [JSON API 1.0](http://jsonapi.org/format/1.0/)

### WIP: Goals for 1.0

* [x] Be 100% JSON API 1.0 spec conform
* [x] Handle/validate a server response body
* [x] Offer an easy way to retrieve the data
* [x] Be extendable and allow injection of classes/models
* [x] Handle/validate a client request body
* [ ] Refactore and remove the deprecated code

### Upgrade notice

JsonApiClient can be considered as stable and version 0.10 will be the last development version.

After version 0.8 there where no breaking changes. Every change was backward compatible and every functionality that will be removed in future now only triggers a deprecated warning.

After version 0.10 is released all deprecated code will be removed and version 1.0 will be released.

This simply means for you:

1. Update to version 0.10
2. Make your code deprecation free
3. You are now ready for upgrading to version 1.0 without breaking your code

(Compare the [Symfony upgrade documentation](https://symfony.com/doc/current/setup/upgrade_major.html))

#### Attention for older versions

Version 0.6.1 and below interprets the pagination links wrong. Make sure you are using the latest version of JsonApiClient. See [#19](https://github.com/Art4/json-api-client/issues/19), [#23](https://github.com/Art4/json-api-client/pull/23) and [#26](https://github.com/Art4/json-api-client/pull/26) for more information.

## Install

Via Composer

``` bash
$ composer require art4/json-api-client
```

## Usage

See the [documentation](docs/README.md).

### Using as parser

```php
use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Helper\Parser;

// The Response body from a JSON API server
$jsonapiString = '{"meta":{"info":"Testing the JsonApiClient library."}}';

try {
    // Use this if you have a response after calling a JSON API server
    $document = Parser::parseResponseString($jsonapiString);

    // Or use this if you have a request to your JSON API server
    $document = Parser::parseRequestString($jsonapiString);
} catch (InputException $e) {
    // $jsonapiString is not valid JSON
} catch (ValidationException $e) {
    // $jsonapiString is not valid JSON API
}
```

**Note**: Using `Art4\JsonApiClient\Helper\Parser` is just a shortcut for directly using the [Manager](docs/manager.md).

`$document` implements the `Art4\JsonApiClient\Accessable` interface to access the parsed data. It has the methods `has($key)`, `get($key)` and `getKeys()`.

```php
// Note that has() and get() have support for dot-notated keys
if ($document->has('meta.info'))
{
    echo $document->get('meta.info'); // "Testing the JsonApiClient library."
}

// you can get all keys as an array
var_dump($document->getKeys());

// array(
//   0 => "meta"
// )
```

### Using as validator

JsonApiClient can be used as a validator for JSON API contents:

```php
use Art4\JsonApiClient\Helper\Parser;

$wrong_jsonapi = '{"data":{},"meta":{"info":"This is wrong JSON API. `data` has to be `null` or containing at least `type` and `id`."}}';

if ( Parser::isValidResponseString($wrong_jsonapi) ) {
// or Parser::isValidRequestString($wrong_jsonapi)
	echo 'string is valid.';
} else {
	echo 'string is invalid json api!';
}

// echoes 'string is invalid json api!'
```

### Extend the client

Need more functionality? Want to directly inject your model? Easily extend JsonApiClient with the [Factory](docs/utils-factory.md).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ phpunit
```

## Contributing

Please feel free to fork and sending Pull Requests. This project follows [Semantic Versioning 2](http://semver.org) and [PSR-2](http://www.php-fig.org/psr/psr-2/).

## Credits

- [Artur Weigandt](https://github.com/Art4) [![Twitter](http://img.shields.io/badge/Twitter-@weigandtlabs-blue.svg)](https://twitter.com/weigandtlabs)
- [All Contributors](../../contributors)

## License

GPL3. Please see [License File](LICENSE) for more information.
