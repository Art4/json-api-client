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

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * JSON API Object
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 */
final class Jsonapi implements JsonapiInterface
{
    use AccessTrait;

    /**
     * @var DataContainerInterface
     */
    protected $container;

    /**
     * @var FactoryManagerInterface
     */
    protected $manager;

    /**
     * Sets the manager and parent
     *
     * @param FactoryManagerInterface $manager The manager
     * @param AccessInterface         $parent  The parent
     */
    public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
    {
        $this->manager = $manager;

        $this->container = new DataContainer();
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
        if (! is_object($object)) {
            throw new ValidationException('Jsonapi has to be an object, "' . gettype($object) . '" given.');
        }

        if (property_exists($object, 'version')) {
            if (is_object($object->version) or is_array($object->version)) {
                throw new ValidationException('property "version" cannot be an object or array, "' . gettype($object->version) . '" given.');
            }

            $this->container->set('version', strval($object->version));
        }

        if (property_exists($object, 'meta')) {
            $meta = $this->manager->getFactory()->make(
                'Meta',
                [$this->manager, $this]
            );
            $meta->parse($object->meta);

            $this->container->set('meta', $meta);
        }

        return $this;
    }

    /**
     * Get a value by the key of this object
     *
     * @param string $key The key of the value
     *
     * @return mixed The value
     */
    public function get($key)
    {
        try {
            return $this->container->get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in this jsonapi object.');
        }
    }
}
