# ResourceCollection
[Back to Navigation](README.md)

## Description

The `ResourceCollection` represents [an array of resource objects, an array of resource identifier objects, or an empty array, for requests that target resource collections](http://jsonapi.org/format/#document-top-level).

Property of:
- [Document object](objects-document.md)

### Properties

_[Symbols definition](objects-introduction.md#symbols)_

|     | Key | Value | Note |
| --- | --- | ----- | ---- |
| *   | `integer` | - [Resource Identifier object](objects-resource-identifier.md)<br />- [Resource Item object](objects-resource-item.md) | Contains only [Resource Identifier object](objects-resource-identifier.md), if the collection is a property of a [Relationship object](objects-relationship.md) |
