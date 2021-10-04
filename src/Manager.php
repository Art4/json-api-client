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

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Input\Input;

/**
 * Manager Interface
 */
interface Manager
{
    /**
     * Parse the input
     *
     * @param \Art4\JsonApiClient\Input\Input $input
     *
     * @throws \Art4\JsonApiClient\Exception\InputException If $input contains invalid JSON API
     * @throws \Art4\JsonApiClient\Exception\ValidationException If $input contains invalid JSON API
     *
     * @return \Art4\JsonApiClient\Accessable
     */
    public function parse(Input $input);

    /**
     * Get a factory from the manager
     *
     * @return \Art4\JsonApiClient\Factory
     */
    public function getFactory();

    /**
     * Get a param by key
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getParam($key, $default);
}
