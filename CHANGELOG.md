# Changelog

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Changed

- **BREAKING**: Introducing the `ElementInterface` to seperate the parsing from the constructor

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
- `\Art4\JsonApiClient\Exception\AccessException` will be thrown instead of `RuntimetException`

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

[Unreleased]: https://github.com/Art4/json-api-client/compare/0.6.3...HEAD
[0.6.3]: https://github.com/Art4/json-api-client/compare/0.6.2...0.6.3
[0.6.2]: https://github.com/Art4/json-api-client/compare/0.6.1...0.6.2
[0.6.1]: https://github.com/Art4/json-api-client/compare/0.6...0.6.1
[0.6]: https://github.com/Art4/json-api-client/compare/0.5...0.6
[0.5]: https://github.com/Art4/json-api-client/compare/0.4...0.5
[0.4]: https://github.com/Art4/json-api-client/compare/0.3...0.4
[0.3]: https://github.com/Art4/json-api-client/compare/0.2...0.3
[0.2]: https://github.com/Art4/json-api-client/compare/0.1...0.2
