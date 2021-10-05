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

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 */
final class ResourceCollection extends AbstractElement
{
    /**
     * Parses the data for this element
     *
     * @param mixed $object The data
     *
     * @throws ValidationException
     */
    protected function parse($object): void
    {
        if (! is_array($object)) {
            throw new ValidationException('Resources for a collection has to be in an array, "' . gettype($object) . '" given.');
        }

        if (count($object) > 0) {
            foreach ($object as $resource) {
                if (! is_object($resource)) {
                    throw new ValidationException('Resources inside a collection MUST be objects, "' . gettype($resource) . '" given.');
                }

                $this->set('', $this->parseResource($resource));
            }
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

    /**
     * Generate a new resource from an object
     */
    private function parseResource(object $data): Accessable
    {
        $object_vars = get_object_vars($data);

        // the 2 properties must be type and id
        // or the 3 properties must be type, id and meta
        if (count($object_vars) === 2 or (count($object_vars) === 3 and property_exists($data, 'meta'))) {
            $resource = $this->create('ResourceIdentifier', $data);
        } else {
            $resource = $this->create('ResourceItem', $data);
        }

        return $resource;
    }
}
