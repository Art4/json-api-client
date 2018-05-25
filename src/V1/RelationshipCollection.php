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
 * Relationship Collection Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 */
final class RelationshipCollection extends AbstractElement
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
            throw new ValidationException('Relationships has to be an object, "' . gettype($object) . '" given.');
        }

        if (property_exists($object, 'type') or property_exists($object, 'id')) {
            throw new ValidationException('These properties are not allowed in attributes: `type`, `id`');
        }

        $object_vars = get_object_vars($object);

        if (count($object_vars) === 0) {
            return $this;
        }

        foreach ($object_vars as $name => $value) {
            if ($this->getParent()->has('attributes.' . $name)) {
                throw new ValidationException('"' . $name . '" property cannot be set because it exists already in parents Resource object.');
            }

            $this->set($name, $this->create('Relationship', $value));
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this relationship collection.');
        }
    }
}
