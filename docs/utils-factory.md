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

Assuming you want a `toJson()` functionality in your document object. First create your own Document class.

```php
<?php

namespace My\Own;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;

class Document implements Art4\JsonApiClient\DocumentInterface
{
    protected $document;

    // Implemention of Art4\JsonApiClient\DocumentInterface

    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
    {
        $this->document = new Art4\JsonApiClient\Document($manager, $parent);
    }

    public function parse($object)
    {
        $this->document->parse($object);

        return $this;
    }

    public function get($key)
    {
        return $this->document->get($key);
    }

    public function has($key)
    {
        return $this->document->has($key);
    }

    public function getKeys()
    {
        return $this->document->getKeys();
    }

    public function asArray()
    {
        return $this->document->asArray();
    }

    // your new method
    public function toJson()
    {
        return json_encode($this->asArray(true));
    }
}
```

Now pass your document class to the factory.

```php
$factory = new \Art4\JsonApiClient\Utils\Factory([
    'Document' => 'My\Own\Document',
]);

// Pass the factory to the manager
$manager = new \Art4\JsonApiClient\Utils\Manager($factory);

$document = $manager->parse($jsonapi_string);

echo get_class($document); // 'My\Own\Document'
echo $document->toJson(); // '{"data":{"type":"posts","id":"5",......'
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
