# DocumentLink
[Back to Navigation](README.md)

## Description

The `DocumentLink` represents a [links object inside the top level document](http://jsonapi.org/format/#document-top-level).

Property of:
- [Document object](objects-document.md)

### Properties

_[Symbols definition](objects-introduction.md#symbols)_

|     | Key | Value | Note |
| --- | --- | ----- | ---- |
| ?   | self | `string` |
| ?   | related | - `string`<br />- [Link object](objects-link.md) |
| ?   | first | - `null`<br />- `string` |
| ?   | last | - `null`<br />- `string` |
| ?   | prev | - `null`<br />- `string` |
| ?   | next | - `null`<br />- `string` |
| *   | `string` | - `string`<br />- [Link object](objects-link.md) |
