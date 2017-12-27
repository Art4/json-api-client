<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\ForwardCompatibility;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\ElementInterface;
use Art4\JsonApiClient\Helper\AccessableTrait;
use Art4\JsonApiClient\Helper\AccessKey;
use Art4\JsonApiClient\Serializer\ArraySerializer;
use Art4\JsonApiClient\Utils\AccessKey as DeprecatedAccessKey;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\V1\Factory as V1Factory;

/**
 * An abstract Element that allows to use the new V1 Elements in the deprecated Elements
 */
abstract class AbstractElement implements ElementInterface
{
    use AccessableTrait {
        get as getFromTrait;
        has as hasFromTrait;
    }

    /**
     * @var Art4\JsonApiClient\FactoryManagerInterface
     */
    private $manager;

    /**
     * @var Art4\JsonApiClient\AccessInterface
     */
    private $parent;

    /**
     * Sets the manager and parent
     *
     * @param FactoryManagerInterface $manager The manager
     * @param AccessInterface         $parent  The parent
     */
    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
    {
        $this->manager = $manager;
        $this->parent = $parent;
    }

    /**
     * Parses the data for this element
     *
     * @param mixed $object The data
     *
     * @throws ValidationException
     *
     * @return self
     */
    public function parse($object)
    {
        $v1Factory = new V1Factory();

        $v1Manager = new Manager($this->manager->getFactory(), [
            'optional_item_id' => $this->manager->getConfig('optional_item_id'),
        ]);

        $element = $v1Factory->make(
            $this->getElementNameForFactory(),
            [$object, $v1Manager, $this->parent]
        );

        foreach ($element->getKeys() as $key) {
            $this->set($key, $element->get($key));
        }

        return $this;
    }

    /**
     * Get the represented Element name for the factory
     *
     * @return string the element name
     */
    abstract protected function getElementNameForFactory();

    /**
     * Check if a value exists
     *
     * @param mixed $key The key
     *
     * @return bool
     */
    public function has($key)
    {
        $key = $this->rebuildKeyIfNeeded($key);

        return $this->hasFromTrait($key);
    }

    /**
     * Get a value by a key
     *
     * @param mixed $key The key
     *
     * @return mixed
     */
    public function get($key)
    {
        $key = $this->rebuildKeyIfNeeded($key);

        return $this->getFromTrait($key);
    }

    /**
     * Convert this object in an array
     *
     * @param bool $fullArray If true, objects are transformed into arrays recursively
     *
     * @return array
     */
    public function asArray($fullArray = false)
    {
        $serializer = new ArraySerializer(['recursive' => (bool) $fullArray]);

        return $serializer->serialize($this);
    }

    /**
     * Rebuild the key if needed
     *
     * @param mixed $key The key as string, AccessKey or DeprecatedAccessKey
     *
     * @return AccessKey
     */
    private function rebuildKeyIfNeeded($key)
    {
        // Build new AccessKey from deprecated one
        if (is_object($key) and $key instanceof DeprecatedAccessKey) {
            $items = [];

            while ($key->count() > 0) {
                $items[] = $key->shift();
                $key->next();
            }

            $key = AccessKey::create(implode('.', $items));
        }

        return $key;
    }
}
