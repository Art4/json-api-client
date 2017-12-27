# Utils\Factory
[Back to Navigation](README.md)

The `Utils\Factory` provides a simple way to override [all objects](objects-introduction.md#all-objects) by injecting your own classes.

### Override the classes

All used classes are listed [in the source code](../src/Utils/Factory.php#L12). You can inject your own classes by passing them to the factory constructor.

```php
$factory = new \Art4\JsonApiClient\Utils\Factory([
    'Document' => 'My\Own\Document'
])
```

#### Example

Assuming you want a `getDescSortedKeys()` functionality in your resource item object. First create your own ResourceItem class.

```php
<?php

namespace My\Own;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;

class ResourceItem implements Art4\JsonApiClient\ResourceItemInterface
{
    protected $item;

    // Implemention of Art4\JsonApiClient\ResourceItemInterface

    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
    {
        $this->item = new Art4\JsonApiClient\ResourceItem($manager, $parent);
    }

    public function parse($object)
    {
        $this->item->parse($object);

        return $this;
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

    public function asArray()
    {
        // `Art4\JsonApiClient\AccessInterface::asArray()` will be removed in v1.0, use `Art4\JsonApiClient\Serializer\ArraySerializer::serialize()` instead
        return $this->item->asArray();
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
$factory = new \Art4\JsonApiClient\Utils\Factory([
    'ResourceItem' => 'My\Own\ResourceItem',
]);

// Pass the factory to the manager
$manager = new \Art4\JsonApiClient\Utils\Manager($factory);

$item = $manager->parse($jsonapi_string)->get('data');

echo get_class($item); // 'My\Own\ResourceItem'
var_dump($item->getDescSortedKeys()); // ['type', 'relationships', 'meta', 'links', 'id', 'attributes']
```

This way you can replace [every object](objects-introduction.md#all-objects) and modify the behaviour of how the JSON API is parsed or how you access the parsed data.

> **Note:** If you modify the parsing you have to take your own care for parsing the [JSON API format](http://jsonapi.org/format) right.

### Create your own factory

If you need to make more changes in the factory you can write your own factory. Just implement the `\Art4\JsonApiClient\Utils\FactoryInterface` in your own factory.

```php
<?php
namespace My\Own
class Factory implements \Art4\JsonApiClient\Utils\FactoryInterface
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
