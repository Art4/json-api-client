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

use Art4\JsonApiClient\Helper\AbstractElement;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Source Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class ErrorSource extends AbstractElement
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
            throw new ValidationException('ErrorSource has to be an object, "' . gettype($object) . '" given.');
        }

        if (property_exists($object, 'pointer')) {
            if (! is_string($object->pointer)) {
                throw new ValidationException('property "pointer" has to be a string, "' . gettype($object->pointer) . '" given.');
            }

            $this->set('pointer', strval($object->pointer));
        }

        if (property_exists($object, 'parameter')) {
            if (! is_string($object->parameter)) {
                throw new ValidationException('property "parameter" has to be a string, "' . gettype($object->parameter) . '" given.');
            }

            $this->set('parameter', strval($object->parameter));
        }
    }

    /**
     * Get a value by the key of this document
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this error source.');
        }
    }
}
