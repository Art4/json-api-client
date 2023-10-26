# Factory
[Back to Navigation](README.md)

The `Art4\JsonApiClient\V1\Factory` provides a simple way to override [all objects](objects-introduction.md#all-objects) by injecting your own classes.

### Override the classes

All possible classes are:

- Attributes
- Document
- DocumentLink
- Error
- ErrorCollection
- ErrorLink
- ErrorSource
- Jsonapi
- Link
- Meta
- Relationship
- RelationshipCollection
- RelationshipLink
- ResourceCollection
- ResourceIdentifier
- ResourceIdentifierCollection
- ResourceItem
- ResourceItemLink
- ResourceNull

You can inject your own classes by passing them to the factory constructor.

```php
use Art4\JsonApiClient\V1\Factory;
use My\Own\Document;

$factory = new Factory([
    'Document' => Document::class,
]);
```

#### Example

Assuming you want a `getDescSortedKeys()` functionality in your resource item object. First create your own ResourceItem class. You can

```php
namespace My\Own;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\V1\ResourceItem as V1ResourceItem;

class ResourceItem extends AbstractElement
{
    protected $item;

    // Implemention of Art4\JsonApiClient\Element

    public function __construct($data, Manager $manager, Accessable $parent)
    {
        $this->item = new V1ResourceItem($data, $manager, $parent);
    }

    public function get($key)
    {
        return $this->item->get($key);
    }

    public function has($key)
    {
        return $this->item->has($key);
    }

    public function getKeys()
    {
        return $this->item->getKeys();
    }

    // your new method
    public function getDescSortedKeys()
    {
        $keys = $this->getKeys();
        rsort($keys);

        return $keys;
    }
}
```

Now pass your resource item class to the factory.

```php
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\V1\Factory;
use My\Own\ResourceItem;

$factory = new Factory([
    'ResourceItem' => ResourceItem::class,
]);

// Pass the factory to the manager
$manager = new ErrorAbortManager($factory);

$document = $manager->parse(new ResponseStringInput($jsonapiString));

$item = $document->get('data');

echo get_class($item); // 'My\Own\ResourceItem'
var_dump($item->getDescSortedKeys()); // ['type', 'relationships', 'meta', 'links', 'id', 'attributes']
```

This way you can replace [every object](objects-introduction.md#all-objects) and modify the behaviour of how the JSON API is parsed or how you access the parsed data.

> **Note:** If you modify the parsing you have to take your own care for parsing the [JSON API format](http://jsonapi.org/format) right.

### Create your own factory

If you need to make more changes in the factory you can write your own factory. Just implement the `\Art4\JsonApiClient\Factory` in your own factory.

```php
namespace My\Own;

use Art4\JsonApiClient\Factory as FactoryInterface;

class Factory implements FactoryInterface
{
    /**
     * Create a new instance of a class
     *
     * @param  string $name The class name
     * @param  array  $args Arguments for the constructor
     * @return object
     */
    public function make($name, array $args = [])
    {
        // create and return a new class
    }
}
```
