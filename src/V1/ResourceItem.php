<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2018  Artur Weigandt  https://wlabs.de/kontakt

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

use Art4\JsonApiClient\DocumentInterface;
use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
final class ResourceItem extends AbstractElement
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
            throw new ValidationException('Resource has to be an object, "' . gettype($object) . '" given.');
        }

        if (! property_exists($object, 'type')) {
            throw new ValidationException('A resource object MUST contain a type');
        }

        if (! is_string($object->type)) {
            throw new ValidationException('A resource type MUST be a string');
        }

        $this->set('type', $object->type);

        if (
            $this->getManager()->getParam('optional_item_id', false) === false
            or ! $this->getParent()->has('data') // If parent has no `data` than parent is a ResourceCollection
        ) {
            if (! property_exists($object, 'id')) {
                throw new ValidationException('A resource object MUST contain an id');
            }

            if (! is_string($object->id)) {
                throw new ValidationException('A resource id MUST be a string');
            }

            $this->set('id', $object->id);
        }

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
        }

        if (property_exists($object, 'attributes')) {
            $this->set('attributes', $this->create('Attributes', $object->attributes));
        }

        if (property_exists($object, 'relationships')) {
            $this->set('relationships', $this->create('RelationshipCollection', $object->relationships));
        }

        if (property_exists($object, 'links')) {
            $this->set('links', $this->create('ResourceItemLink', $object->links));
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this resource.');
        }
    }
}
