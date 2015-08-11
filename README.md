# JSON API Client

[![Latest Version][ico-version]][link-version]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]

JSON API Client is a PHP Library to handle the response body from a [JSON API](http://jsonapi.org) Server.

Format: [JSON API](http://jsonapi.org/format) 1.0

## Install

Via Composer

``` bash
$ composer require art4/json-api-client
```

## Usage

Todo: Documentation

### Using as reader

```php
// The Response body from a JSON API server
$jsonapi_string = '{"meta":{"info":"Testing the JSON API Client."}}';

$document = \Art4\JsonApiClient\Utils\Helper::parse($jsonapi_string);

if ($document->hasMeta() and $document->getMeta()->hasInfo())
{
    echo $document->getMeta()->getInfo();
}

// "Testing the JSON API Client."
```

### Using as validator

JSON API Client can be used as a validator for JSON API contents:

```php
$wrong_jsonapi = '{"data":{},"meta":{"info":"This is wrong JSON API. `data` has to be `null` or containing at least `type` and `id`."}}';

try
{
	$document = \Art4\JsonApiClient\Utils\Helper::parse($wrong_jsonapi);
}
catch (\InvalidArgumentException $e)
{
	echo $e->getMessage(); // "A resource object MUST contain a type"
}
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ phpunit
```

## Contributing

Please feel free to fork and sending Pull Requests.

## Credits

- [Artur Weigandt][link-author]
- [All Contributors][link-contributors]

## License

GPL2. Please see [License File](LICENSE) for more information.

[ico-version]: https://img.shields.io/github/release/Art4/json-api-client.svg
[ico-license]: https://img.shields.io/badge/license-GPL2-brightgreen.svg
[ico-travis]: https://travis-ci.org/Art4/json-api-client.svg
[link-version]: https://github.com/Art4/json-api-client/releases
[link-travis]: https://travis-ci.org/Art4/json-api-client
[link-author]: https://github.com/Art4
[link-contributors]: ../../contributors
