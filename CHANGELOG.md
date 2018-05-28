# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- New class `Art4\JsonApiClient\Serializer\ArraySerializer` to create an array from an `Art4\JsonApiClient\Accessable`
- New class `Art4\JsonApiClient\V1\Attributes` to represent an Attributes element
- New class `Art4\JsonApiClient\V1\Document` to represent a Document element
- New class `Art4\JsonApiClient\V1\DocumentLink` to represent a DocumentLink element
- New class `Art4\JsonApiClient\V1\Error` to represent an Error element
- New class `Art4\JsonApiClient\V1\ErrorCollection` to represent an ErrorCollection element
- New class `Art4\JsonApiClient\V1\ErrorLink` to represent an ErrorLink element
- New class `Art4\JsonApiClient\V1\ErrorSource` to represent an ErrorSource element
- New class `Art4\JsonApiClient\V1\Jsonapi` to represent a Jsonapi element
- New class `Art4\JsonApiClient\V1\Link` to represent a Link element
- New class `Art4\JsonApiClient\V1\Meta` to represent a Meta element
- New class `Art4\JsonApiClient\V1\Relationship` to represent a Relationship element
- New class `Art4\JsonApiClient\V1\RelationshipCollection` to represent a RelationshipCollection element
- New class `Art4\JsonApiClient\V1\RelationshipLink` to represent a RelationshipLink element
- New class `Art4\JsonApiClient\V1\ResourceCollection` to represent a ResourceCollection element
- New class `Art4\JsonApiClient\V1\ResourceIdentifier` to represent a ResourceIdentifier element
- New class `Art4\JsonApiClient\V1\ResourceIdentifierCollection` to represent a ResourceIdentifierCollection element
- New class `Art4\JsonApiClient\V1\ResourceItem` to represent a ResourceItem element
- New class `Art4\JsonApiClient\V1\ResourceItemLink` to represent a ResourceItemLink element
- New class `Art4\JsonApiClient\V1\ResourceNull` to represent a ResourceNull element

### Changed

- Support for PHP 5.5 dropped, PHP >=5.6 is now required

### Deprecated

- `Art4\JsonApiClient\AccessInterface::asArray()` will be removed in v1.0, use `Art4\JsonApiClient\Serializer\ArraySerializer::serialize()` instead
- `Art4\JsonApiClient\AccessInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Attributes` will be removed in v1.0, use `Art4\JsonApiClient\V1\Attributes` instead
- `Art4\JsonApiClient\AttributesInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Document` will be removed in v1.0, use `Art4\JsonApiClient\V1\Document` instead
- `Art4\JsonApiClient\DocumentInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\DocumentLink` will be removed in v1.0, use `Art4\JsonApiClient\V1\DocumentLink` instead
- `Art4\JsonApiClient\DocumentLinkInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ElementInterface` will be removed in v1.0, use `Art4\JsonApiClient\Element` instead
- `Art4\JsonApiClient\Error` will be removed in v1.0, use `Art4\JsonApiClient\V1\Error` instead
- `Art4\JsonApiClient\ErrorCollection` will be removed in v1.0, use `Art4\JsonApiClient\V1\ErrorCollection` instead
- `Art4\JsonApiClient\ErrorCollectionInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ErrorInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ErrorLink` will be removed in v1.0, use `Art4\JsonApiClient\V1\ErrorLink` instead
- `Art4\JsonApiClient\ErrorLinkInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ErrorSource` will be removed in v1.0, use `Art4\JsonApiClient\V1\ErrorSource` instead
- `Art4\JsonApiClient\ErrorSourceInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Jsonapi` will be removed in v1.0, use `Art4\JsonApiClient\V1\Jsonapi` instead
- `Art4\JsonApiClient\JsonapiInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Link` will be removed in v1.0, use `Art4\JsonApiClient\V1\Link` instead
- `Art4\JsonApiClient\LinkInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Meta` will be removed in v1.0, use `Art4\JsonApiClient\V1\Meta` instead
- `Art4\JsonApiClient\MetaInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Relationship` will be removed in v1.0, use `Art4\JsonApiClient\V1\Relationship` instead
- `Art4\JsonApiClient\RelationshipCollection` will be removed in v1.0, use `Art4\JsonApiClient\V1\RelationshipCollection` instead
- `Art4\JsonApiClient\RelationshipCollectionInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\RelationshipInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\RelationshipLink` will be removed in v1.0, use `Art4\JsonApiClient\V1\RelationshipLink` instead
- `Art4\JsonApiClient\RelationshipLinkInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ResourceCollection` will be removed in v1.0, use `Art4\JsonApiClient\V1\ResourceCollection` instead
- `Art4\JsonApiClient\ResourceCollectionInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ResourceIdentifier` will be removed in v1.0, use `Art4\JsonApiClient\V1\ResourceIdentifier` instead
- `Art4\JsonApiClient\ResourceIdentifierCollection` will be removed in v1.0, use `Art4\JsonApiClient\V1\ResourceIdentifierCollection` instead
- `Art4\JsonApiClient\ResourceIdentifierCollectionInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ResourceIdentifierInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ResourceItem` will be removed in v1.0, use `Art4\JsonApiClient\V1\ResourceItem` instead
- `Art4\JsonApiClient\ResourceItemInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ResourceItemLink` will be removed in v1.0, use `Art4\JsonApiClient\V1\ResourceItemLink` instead
- `Art4\JsonApiClient\ResourceItemLinkInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\ResourceNull` will be removed in v1.0, use `Art4\JsonApiClient\V1\ResourceNull` instead
- `Art4\JsonApiClient\ResourceNullInterface` will be removed in v1.0, use `Art4\JsonApiClient\Accessable` instead
- `Art4\JsonApiClient\Utils\AccessKey` will be removed in v1.0
- `Art4\JsonApiClient\Utils\AccessTrait` will be removed in v1.0
- `Art4\JsonApiClient\Utils\DataContainer` will be removed in v1.0
- `Art4\JsonApiClient\Utils\DataContainerInterface` will be removed in v1.0
- `Art4\JsonApiClient\Utils\Factory` will be removed in v1.0, use `Art4\JsonApiClient\V1\Factory` instead
- `Art4\JsonApiClient\Utils\FactoryInterface` will be removed in v1.0, use `Art4\JsonApiClient\Factory` instead
- `Art4\JsonApiClient\Utils\FactoryManagerInterface` will be removed in v1.0
- `Art4\JsonApiClient\Utils\Helper::decodeJson()` will be removed in v1.0, use `Art4\JsonApiClient\Input\ResponseStringInput::getAsObject()` instead
- `Art4\JsonApiClient\Utils\Manager` will be removed in v1.0, use `Art4\JsonApiClient\Manager\ErrorAbortManager` instead
- `Art4\JsonApiClient\Utils\ManagerInterface` will be removed in v1.0, use `Art4\JsonApiClient\Manager` instead

## [0.9.1] - 2017-12-21

### Changed

- Change Code Style to PSR-2
- Tests in Travis for PHP 7.2 and nightly added

## [0.9] - 2017-06-06

### Added

- New method `Utils\Helper::parseResponseBody()` to parse a JSON API response body
- New method `Utils\Helper::isValidResponseBody()` to validate a JSON API response body
- New method `Utils\Helper::isValidRequestBody()` to validate a JSON API request body with optional item id

### Deprecated

- `Utils\Helper::parse()` will be removed in v1.0, use `Utils\Helper::parseResponseBody()` instead
- `Utils\Helper::isValid()` will be removed in v1.0, use `Utils\Helper::isValidResponseBody()` instead

## [0.8.1] - 2017-06-01

### Fixed

- Fixed a bug in the decission if a `data` attribute is a `ResourceItem` or `ResourceIdentifier`

## [0.8] - 2017-05-29

### Added

- New method `Utils\Helper::parseRequestBody()` to parse JSON API with optional item id
- Run tests with Travis in PHP 7.1

### Changed

- Drop support for PHP 5.4
- **BREAKING**: New method `Utils\ManagerInterface::getConfig()` to get a config value
- **BREAKING**: New method `Utils\ManagerInterface::setConfig()` to set a config value

## [0.7] - 2016-11-24

### Changed

- Update license to GPLv3
- **BREAKING**: Introducing the `ElementInterface` to seperate the parsing from the constructor
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\Collection` to `Art4\JsonApiClient\ResourceCollection`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\CollectionInterface` to `Art4\JsonApiClient\ResourceCollectionInterface`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\Identifier` to `Art4\JsonApiClient\ResourceIdentifier`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\IdentifierInterface` to `Art4\JsonApiClient\ResourceIdentifierInterface`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\IdentifierCollection` to `Art4\JsonApiClient\ResourceIdentifierCollection`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\IdentifierCollectionInterface` to `Art4\JsonApiClient\ResourceIdentifierCollectionInterface`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\Item` to `Art4\JsonApiClient\ResourceItem`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\ItemInterface` to `Art4\JsonApiClient\ResourceItemInterface`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\ItemLink` to `Art4\JsonApiClient\ResourceItemLink`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\ItemLinkInterface` to `Art4\JsonApiClient\ResourceItemLinkInterface`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\NullResource` to `Art4\JsonApiClient\ResourceNull`
- **BREAKING**: Rename `Art4\JsonApiClient\Resource\NullResourceInterface` to `Art4\JsonApiClient\ResourceNullInterface`

### Removed

- **BREAKING**: Remove the `Resource\ResourceInterface` and its methods

## [0.6.3] - 2016-04-26

### Fixed

- Prevent PHP bug in json_decode(), if option JSON_BIGINT_AS_STRING is not implemented, see [#28](https://github.com/Art4/json-api-client/issues/28)

## [0.6.2] - 2016-04-15

### Fixed

- links in document object can contain objects, see [#26](https://github.com/Art4/json-api-client/pull/26)

## [0.6.1] - 2015-12-28

### Added

- New `Resource\ItemLink` object; was seperated from `Link` object

### Fixed

- links and pagination are now parsed spec conform, see [#23](https://github.com/Art4/json-api-client/pull/23)

## [0.6] - 2015-11-06

### Added

- `Helper::isValid()` checks if a string is valid JSON API

### Fixed

- **BREAKING**: pagination links moved from `Pagination` to `DocumentLink` and `RelationshipLink`

### Removed

- **BREAKING**: object `Pagination` was removed

## [0.5] - 2015-10-12

### Added

- Dot-notation support in `AccessInterface::has()` and `AccessInterface::get()`
- Every object has now his own interface, eg. `DocumentInterface`, `MetaInterface` or `Resource\ItemInterface`

### Changed

- **BREAKING**: object `PaginationLink` was renamed to `Pagination`
- **BREAKING**: nearly all classes was set to `final` and can't be extended anymore. Implement the new interfaces instead. See also [#18](https://github.com/Art4/json-api-client/pull/18).

## [0.4] - 2015-09-01

### Added

- Introduce `Utils\Manager` and `Utils\Factory` for injecting own classes into the client
- Every object inside the document implements `AccessInterface`
- `AccessInterface` supports `asArray()` for transforming an object into an array
- A `Resource\IdentifierCollection` that is either empty or holds only `Resource\Identifier` objects

### Fixed

- `Relationship::get('data')` returns a `Resource\IdentifierCollection` object instead of an array

## [0.3] - 2015-08-24

### Added

- Better documentation

### Changed

- `Document::get('data')` returns always a `ResourceInterface` object
- `Document::get('error')` returns a `ErrorCollection` object
- `Document::get('included')` returns a `Resource\Collection` object
- `\Art4\JsonApiClient\Exception\ValidationException` will be thrown instead of `InvalidArgumentException`
- `\Art4\JsonApiClient\Exception\AccessException` will be thrown instead of `RuntimeException`

## [0.2] - 2015-08-12

### Added

- Documentation, see folder [docs/](docs/README.md)
- Every object has got a `get()` and `has()` method for better value access
- Every object can list his own keys with `keyKeys()`

### Removed

- All old getter like `getMeta()` or `hasId()` were removed

## 0.1 - 2015-08-11

### Added

- Validator fits nearly 100% specification
- Full test coverage

[Unreleased]: https://github.com/Art4/json-api-client/compare/0.9.1...HEAD
[0.9.1]: https://github.com/Art4/json-api-client/compare/0.9...0.9.1
[0.9]: https://github.com/Art4/json-api-client/compare/0.8.1...0.9
[0.8.1]: https://github.com/Art4/json-api-client/compare/0.8...0.8.1
[0.8]: https://github.com/Art4/json-api-client/compare/0.7...0.8
[0.7]: https://github.com/Art4/json-api-client/compare/0.6.3...0.7
[0.6.3]: https://github.com/Art4/json-api-client/compare/0.6.2...0.6.3
[0.6.2]: https://github.com/Art4/json-api-client/compare/0.6.1...0.6.2
[0.6.1]: https://github.com/Art4/json-api-client/compare/0.6...0.6.1
[0.6]: https://github.com/Art4/json-api-client/compare/0.5...0.6
[0.5]: https://github.com/Art4/json-api-client/compare/0.4...0.5
[0.4]: https://github.com/Art4/json-api-client/compare/0.3...0.4
[0.3]: https://github.com/Art4/json-api-client/compare/0.2...0.3
[0.2]: https://github.com/Art4/json-api-client/compare/0.1...0.2
