# JSON API Client

[![Latest Version][ico-version]][link-version]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]

JSON API Client is a PHP Library to handle the response body from a [JSON API](http://jsonapi.org) Server.

Format: [JSON API](http://jsonapi.org/format) 1.0

## Work in Progress ![Progress](http://progressed.io/bar/56)

Object structure: https://gist.github.com/Art4/a77052f1e8700bdde498

Object | Implementation<br />![Progress](http://progressed.io/bar/59) | Tests<br />![Progress](http://progressed.io/bar/53)
------------------------|:------------------------------------------|:-----------------------------------------
Document                | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Resource Identifier     | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Resource                | ![Progress](http://progressed.io/bar/30)  | ![Progress](http://progressed.io/bar/0)
Attributes              | ![Progress](http://progressed.io/bar/0)   | ![Progress](http://progressed.io/bar/0)
Relationship Collection | ![Progress](http://progressed.io/bar/0)   | ![Progress](http://progressed.io/bar/0)
Relationship            | ![Progress](http://progressed.io/bar/0)   | ![Progress](http://progressed.io/bar/0)
Error                   | ![Progress](http://progressed.io/bar/50)  | ![Progress](http://progressed.io/bar/0)
Error Source            | ![Progress](http://progressed.io/bar/0)   | ![Progress](http://progressed.io/bar/0)
Link                    | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Document Link           | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Relationship Link       | ![Progress](http://progressed.io/bar/0)   | ![Progress](http://progressed.io/bar/0)
Error Link              | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Pagination Link         | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Jsonapi                 | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)
Meta                    | ![Progress](http://progressed.io/bar/100) | ![Progress](http://progressed.io/bar/100)

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

[ico-version]: https://img.shields.io/github/release/Art4/json-api-client.svg
[ico-license]: https://img.shields.io/badge/license-GPL2-brightgreen.svg
[ico-travis]: https://travis-ci.org/Art4/json-api-client.svg
[link-version]: https://github.com/Art4/json-api-client/releases
[link-travis]: https://travis-ci.org/Art4/json-api-client
[link-author]: https://github.com/Art4
[link-contributors]: ../../contributors
