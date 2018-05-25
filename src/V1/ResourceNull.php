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
use Art4\JsonApiClient\Element;
use Art4\JsonApiClient\Manager;
use Art4\JsonApiClient\Exception\AccessException;

/**
 * Null Resource
 */
final class ResourceNull implements Accessable, Element
{
    /**
     * Constructor
     *
     * @param mixed                         $data    The data for this Element
     * @param Art4\JsonApiClient\Manager    $manager The manager
     * @param Art4\JsonApiClient\Accessable $parent  The parent
     */
    public function __construct($data, Manager $manager, Accessable $parent)
    {
    }

    /**
     * Check if a value exists in this resource
     *
     * @param string $key The key of the value
     *
     * @return bool false
     */
    public function has($key)
    {
        return false;
    }

    /**
     * Returns the keys of all setted values in this resource
     *
     * @return array Keys of all setted values
     */
    public function getKeys()
    {
        return [];
    }

    /**
     * Get a value by the key of this identifier
     *
     * @param string $key The key of the value
     */
    public function get($key)
    {
        throw new AccessException('A ResourceNull has no values.');
    }
}
