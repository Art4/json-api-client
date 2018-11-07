# DocumentLink
[Back to Navigation](README.md)

## Description

The `DocumentLink` represents a [links object inside the top level document](http://jsonapi.org/format/#document-top-level).

This object implements the [Accessable interface](objects-introduction.md#value-access).

Property of:
- [Document object](objects-document.md)

### Properties

_[Symbols definition](objects-introduction.md#symbols)_

You can use the [Accessable interface](objects-introduction.md#value-access) to access this properties.

|     | Key | Value | Note |
| --- | --- | ----- | ---- |
| ?   | self | `string` |
| ?   | related | - `string`<br />- [Link object](objects-link.md) |
| ?   | first | - `null`<br />- `string` |
| ?   | last | - `null`<br />- `string` |
| ?   | prev | - `null`<br />- `string` |
| ?   | next | - `null`<br />- `string` |
| *   | `string` | - `string`<br />- [Link object](objects-link.md) |
