# Resource\Collection
[Back to Navigation](README.md)

## Description

The `Resource\Collection` represents [an array of resource objects, an array of resource identifier objects, or an empty array, for requests that target resource collections](http://jsonapi.org/format/#document-top-level). It implements the `Resource\ResourceInterface`.

- extends:
- extended by:
- property of:
  - [Document object](objects-document.md)
  - [Relationship object](objects-relationship.md)

### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
* | `integer` | - [Resource Identifier object](objects-resource-identifier.md)<br />- [Resource Item object](objects-resource-item.md) | Contains only [Resource Identifier object](objects-resource-identifier.md), if the collection is a property of a [Relationship object](objects-relationship.md)
