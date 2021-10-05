<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2021  Artur Weigandt  https://wlabs.de/kontakt

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
 * Error Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class Error extends AbstractElement
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
        if (! is_object($object)) {
            throw new ValidationException(
                'Error has to be an object, "' . gettype($object) . '" given.'
            );
        }

        if (property_exists($object, 'id')) {
            if (! is_string($object->id)) {
                throw new ValidationException(
                    'property "id" has to be a string, "' .
                    gettype($object->id) . '" given.'
                );
            }

            $this->set('id', strval($object->id));
        }

        if (property_exists($object, 'links')) {
            $this->set('links', $this->create('ErrorLink', $object->links));
        }

        if (property_exists($object, 'status')) {
            if (! is_string($object->status)) {
                throw new ValidationException(
                    'property "status" has to be a string, "' .
                    gettype($object->status) . '" given.'
                );
            }

            $this->set('status', strval($object->status));
        }

        if (property_exists($object, 'code')) {
            if (! is_string($object->code)) {
                throw new ValidationException(
                    'property "code" has to be a string, "' .
                    gettype($object->code) . '" given.'
                );
            }

            $this->set('code', strval($object->code));
        }

        if (property_exists($object, 'title')) {
            if (! is_string($object->title)) {
                throw new ValidationException(
                    'property "title" has to be a string, "' .
                    gettype($object->title) . '" given.'
                );
            }

            $this->set('title', strval($object->title));
        }

        if (property_exists($object, 'detail')) {
            if (! is_string($object->detail)) {
                throw new ValidationException(
                    'property "detail" has to be a string, "' .
                    gettype($object->detail) . '" given.'
                );
            }

            $this->set('detail', strval($object->detail));
        }

        if (property_exists($object, 'source')) {
            $this->set('source', $this->create('ErrorSource', $object->source));
        }

        if (property_exists($object, 'meta')) {
            $this->set('meta', $this->create('Meta', $object->meta));
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
            throw new AccessException(
                '"' . $key . '" doesn\'t exist in this error object.'
            );
        }
    }
}
