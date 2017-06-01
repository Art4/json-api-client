# RelationshipLink
[Back to Navigation](README.md)

## Description

The `RelationhipLink` represents a [links object inside a relationship object](http://jsonapi.org/format/#document-resource-object-relationships).

Property of:
- [Relationship object](objects-relationship.md)

### Properties

_[Symbols definition](objects-introduction.md#symbols)_

|     | Key | Value | Note |
| --- | --- | ----- | ---- |
| #   | self | `string` | |
| #   | related | - `string`<br />- [Link object](objects-link.md) | |
| ?   | first | - `null`<br />- `string` | Only exists if the parent relationship object represents a to-many relationship |
| ?   | last | - `null`<br />- `string` | Only exists if the parent relationship object represents a to-many relationship |
| ?   | prev | - `null`<br />- `string` | Only exists if the parent relationship object represents a to-many relationship |
| ?   | next | - `null`<br />- `string` | Only exists if the parent relationship object represents a to-many relationship |
| *   | `string` | - `string`<br />- [Link object](objects-link.md) | |
