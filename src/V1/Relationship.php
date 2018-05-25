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

namespace Art4\JsonApiClient\V1;

use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
final class Relationship extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @param mixed $object The data
     *
     * @throws ValidationException
     */
    protected function parse($object)
    {
        if (! is_object($object)) {
            throw new ValidationException('Relationship has to be an object, "' . gettype($object) . '" given.');
        }

        if (! property_exists($object, 'links') and ! property_exists($object, 'data') and ! property_exists($object, 'meta')) {
            throw new ValidationException('A Relationship object MUST contain at least one of the following properties: links, data, meta');
        }

        if (property_exists($object, 'data')) {
            if ($object->data === null) {
                $this->set('data', $object->data);
            } elseif (is_array($object->data)) {
                $this->set('data', $this->create('ResourceIdentifierCollection', $object->data));
            } else {
                $this->set('data', $this->create('ResourceIdentifier', $object->data));
            }
        }

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
        }

        // Parse 'links' after 'data'
        if (property_exists($object, 'links')) {
            $this->set('links', $this->create('RelationshipLink', $object->links));
        }
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
            return parent::get($key);
        } catch (AccessException $e) {
            throw new AccessException('"' . $key . '" doesn\'t exist in Relationship.');
        }
    }
}
