# RelationshipLink
[Back to Navigation](README.md)

## Description

The `RelationhipLink` represents a [links object inside a relationship object](http://jsonapi.org/format/#document-resource-object-relationships).

- extends: [Link object](objects-link.md)
- extended by:
- property of: [Relationship object](objects-relationship.md)

### Properties

    | Key | Value | Note
--- | ---- | ----- | ----
# | self | `string` |
# | related | `string` |
? | pagination | [Pagination Link object](objects-pagination-link.md) | Only exists if the parent relationship object represents a to-many relationship
