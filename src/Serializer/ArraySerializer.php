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

namespace Art4\JsonApiClient\Serializer;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\V1\ResourceNull;

final class ArraySerializer implements Serializer
{
    /** @var array<string, mixed> */
    private array $config = [
        'recursive' => false,
    ];

    /**
     * Setup the serializer
     *
     * @param array<string, mixed> $params
     */
    public function __construct(array $params = [])
    {
        foreach ($this->config as $key => $value) {
            if (array_key_exists($key, $params)) {
                $this->config[$key] = $params[$key];
            }
        }
    }

    /**
     * Convert data in an array
     *
     * @param \Art4\JsonApiClient\Accessable $data The data for serialization
     *
     * @return array<string, mixed>|null
     */
    public function serialize(Accessable $data)
    {
        $fullArray = (bool) $this->config['recursive'];

        if ($data instanceof ResourceNull) {
            return null;
        }

        $array = [];

        foreach ($data->getKeys() as $key) {
            $value = $data->get($key);

            if ($fullArray) {
                $array[$key] = $this->objectTransform($value);
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * Transforms objects to arrays
     *
     * @param mixed $val
     *
     * @return mixed
     */
    private function objectTransform($val)
    {
        if (! is_object($val)) {
            return $val;
        } elseif ($val instanceof Accessable) {
            return $this->serialize($val);
        } else {
            // Fallback for stdClass objects
            return json_decode(json_encode($val), true);
        }
    }
}
