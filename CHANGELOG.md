# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][]

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

[Unreleased]: https://github.com/Art4/json-api-client/compare/0.4...HEAD
[0.4]: https://github.com/Art4/json-api-client/compare/0.3...0.4
[0.3]: https://github.com/Art4/json-api-client/compare/0.2...0.3
[0.2]: https://github.com/Art4/json-api-client/compare/0.1...0.2
