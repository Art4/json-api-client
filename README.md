# JSON API Client

JSON API Client is a PHP Library to handle the response body from a [JSON API](http://jsonapi.org) Server.

Format: [JSON API](http://jsonapi.org/format) 1.0

WIP (estimated): ![Progress](http://progressed.io/bar/12)

## Install (Todo)

Via Composer

``` bash
$ composer require art4/json-api-client
```

## Usage

```php
$jsonapi_string = '{"meta":{"info":"Testing the JSON API Client."}}';

$document = \Art4\JsonApiClient\Utils\Helper::parse($jsonapi_string);

if ($document->hasMeta() and $document->getMeta()->hasInfo())
{
    echo $document->getMeta()->getInfo();
}

// Testing the JSON API Client.
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

[link-author]: https://github.com/Art4
[link-contributors]: ../../contributors
