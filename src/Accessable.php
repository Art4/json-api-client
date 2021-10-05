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

namespace Art4\JsonApiClient;

/**
 * Accessable Interface
 */
interface Accessable
{
    /**
     * Get a value by a key
     *
     * @param mixed $key The key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Check if a value exists
     *
     * @deprecated `\Art4\JsonApiClient\Accessable::has()` will add `bool` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @param mixed $key The key
     *
     * @return bool
     */
    public function has($key);
    // public function has($key): bool;

    /**
     * Returns the keys of all setted values
     *
     * @deprecated `\Art4\JsonApiClient\Accessable::getKeys()` will add `array` as a native return type declaration in v2.0. Do the same in your implementation now to avoid errors.
     *
     * @return array<string> Keys of all setted values
     */
    public function getKeys();
    // public function getKeys(): array;
}
