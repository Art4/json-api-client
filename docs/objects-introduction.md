## Objects introduction
[Back to Navigation](README.md)

JSON API Client will parse a JSON API content into a hierarchical object stucture. **Every object has these methods for getting the values:**

- `has($key)`
- `get($key)`
- `getKeys()`

### Check if a value exist

You can check for all possible values using the `has()` method.

```php
$jsonapi_string = '{"meta":{"info":"Testing the JSON API Client."}}';

$document = \Art4\JsonApiClient\Utils\Helper::parse($jsonapi_string);

var_dump($document->has('meta'));
```

This returns:

```php
true
```

### Get the keys of all existing values

You can get the keys of all existing values using the `getKeys()` method. Assume we have the same `$document` like in the last example.

```php
var_dump($document->getKeys());
```

This returns:

```php
array(
  0 => 'meta'
)
```

This can be useful to get available values:

```php
foreach($document->getKeys() as $key)
{
	$value = $document->get($key);
}
```

### Get the containing data

You can get all (existing) data using the `get()` method.

```php
$meta = $document->get('meta');

// $meta contains a meta object.
```

> **Note:** Using `get()` on a non-existing value will throw an `RuntimeException`. Use `has()` or `getKeys()` to check if a value exists.

## Object Structure

All possible objects and there hierarchical structure are listet below.

### Symbols

| Symbol | Description |
| ------ | ----------- |
| 1      | at least one of these properties is required |
| *      | zero, one or more properties |
| +      | required |
| -      | optional |
| !      | not allowed |

### All objects

1. [Document object](#document-object)
1. [Resource Identifier object](#resource-identifier-object)
1. [Resource object](#resource-object)
1. [Attributes object](#attributes-object)
1. [Relationship Collection object](#relationship-collection-object)
1. [Relationship object](#relationship-object)
1. [Error object](#error-object)
1. [Error Source object](#error-source-object)
1. [Link object](#link-object)
1. [Document Link object](#document-link-object)
1. [Relationship Link object](#relationship-link-object)
1. [Error Link object](#error-link-object)
1. [Pagination Link object](#pagination-link-object)
1. [Jsonapi object](#jsonapi-object)
1. [Meta object](#meta-object)

### Document object

- extends:
- extended by:
- property of:

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
1 | data | - `null`<br />- [Resource Identifier object](#resource-identifier-object)<br />- [Resource object](#resource-object)<br />- array()<br />- array([Resource Identifier object](#resource-identifier-object))<br />- array([Resource object](#resource-object)) | not allowed, if 'errors' exists
1 | errors | array([Error object](#error-object)) | not allowed, if 'data' exists
1 | meta | [Meta object](#meta-object) |
- | jsonapi | [Jsonapi object](#jsonapi-object) |
- | links | [Document Link object](#document-link-object) |
- | included | array([Resource object](#resource-object)) | not allowed, if 'data' doesn't exist

### Resource Identifier object

- extends:
- extended by: [Resource object](#resource-object)
- property of:
  - [Document object](#document-object)
  - [Relationship object](#relationship-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
+ | type | `string` |
+ | id | `string` |
- | meta | [Meta object](#meta-object) |

### Resource object

- extends: [Resource Identifier object](#resource-identifier-object)
- extended by:
- property of: [Document object](#document-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
- | attributes | [Attributes object](#attributes-object) |
- | relationships | [Relationship Collection object](#relationship-collection-object) |
- | links | [Link object](#link-object) |

### Attributes object

- extends: [Meta object](#meta-object)
- extended by:
- property of: [Resource object](#resource-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
* | `string` | mixed |
! | type |   | already used in [Resource object](#resource-object) |
! | id | | already used in [Resource object](#resource-object) |
! | relationships | | reserved by spec for future use |
! | links | | reserved by spec for future use |

### Relationship Collection object

- extends:
- extended by:
- property of: [Resource object](#resource-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
* | `string` | [Relationship object](#relationship-object) | not allowed, if already used in parents [Attributes object](#attributes-object) property)
! | type |   | already used in [Resource object](#resource-object)
! | id |   | already used in [Resource object](#resource-object)

### Relationship object

- extends:
- extended by:
- property of: [Relationship Collection object](#relationship-collection-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
1 | links | [Relationship Link object](#relationship-link-object) |
1 | data | - `null`<br />- [Resource Identifier object](#resource-identifier-object)<br />- array()<br />- array([Resource Identifier object](#resource-identifier-object)) |
1 | meta | [Meta object](#meta-object) |

### Error object

- extends:
- extended by:
- property of: [Document object](#document-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
- | id | `string` |
- | links | [Error Link object](#error-link-object) |
- | status | `string` |
- | code | `string` |
- | title | `string` |
- | detail | `string` |
- | source | [Error Source object](#error-source-object) |
- | meta | [Meta object](#meta-object) |

### Error Source object

- extends:
- extended by:
- property of: [Error object](#error-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
- | pointer | `string` |
- | parameter | `string` |

### Link object

- extends:
- extended by:
  - [Document Link object](#document-link-object)
  - [Relationship Link object](#relationship-link-object)
  - [Error Link object](#error-link-object)
  - [Pagination Link object](#pagination-link-object)
- property of:
  - [Resource object](#resource-object)
  - [Link object](#link-object)
  - [Error Link object](#error-link-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
* | `string` | - `string`<br />- [Link object](#link-object) |
- | href | `string` |
- | meta | [Meta object](#meta-object) |

### Document Link object

- extends: [Link object](#link-object)
- extended by:
- property of: [Document object](#document-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
- | self | `string` |
- | related | `string` |
- | pagination | [Pagination Link object](#pagination-link-object) |

### Relationship Link object

- extends: [Link object](#link-object)
- extended by:
- property of: [Relationship object](#relationship-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
1 | self | `string` |
1 | related | `string` |
- | pagination | [Pagination Link object](#pagination-link-object) | Only exists if the parent relationship object represents a to-many relationship 

### Error Link object

- extends: [Link object](#link-object)
- extended by:
- property of: [Error object](#error-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
+ | about | - `string`<br />- [Link object](#link-object) |

### Pagination Link object

- extends: [Link object](#link-object)
- extended by:
- property of:
  - [Document Link object](#document-link-object)
  - [Relationship Link object](#relationship-link-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
- | first | - `null`<br />- `string` |
- | last | - `null`<br />- `string` |
- | prev | - `null`<br />- `string` |
- | next | - `null`<br />- `string` |

### Jsonapi object

- extends:
- extended by:
- property of: [Document object](#document-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
- | version | `string` | Default: `"1.0"`
- | meta | [Meta object](#meta-object) |

### Meta object

- extends:
- extended by: [Attributes object](#attributes-object)
- property of:
  - [Document object](#document-object)
  - [Resource Identifier object](#resource-identifier-object)
  - [Relationship object](#relationship-object)
  - [Error object](#error-object)
  - [Link object](#link-object)
  - [Jsonapi object](#jsonapi-object)

#### Properties

    | Name | Value | Note
--- | ---- | ----- | ----
* | `string` | `mixed` |
