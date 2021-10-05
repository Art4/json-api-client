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
 * ItemLink Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
final class ResourceItemLink extends AbstractElement
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
            throw new ValidationException('ItemLink has to be an object, "' . gettype($object) . '" given.');
        }

        foreach (get_object_vars($object) as $name => $value) {
            $this->setLink($name, $value);
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
            throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
        }
    }

    /**
     * Set a link
     *
     * @param string              $name The Name
     * @param string|object|mixed $link The Link
     */
    private function setLink(string $name, $link): void
    {
        // from spec: aA link MUST be represented as either:
        // - a string containing the link's URL.
        // - an object ("link object") which can contain the following members:
        if (! is_object($link) and ! is_string($link)) {
            throw new ValidationException('Link attribute has to be an object or string, "' . gettype($link) . '" given.');
        }

        if (is_string($link)) {
            parent::set($name, strval($link));

            return;
        }

        // Now $link can only be an object
        $this->set($name, $this->create('Link', $link));
    }
}
