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

namespace Art4\JsonApiClient\Utils;

@trigger_error(__NAMESPACE__ . '\ManagerInterface is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Manager instead', E_USER_DEPRECATED);

/**
 * Manager Interface
 *
 * @deprecated ManagerInterface is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Manager instead
 */
interface ManagerInterface
{
    /**
     * Parse a JSON API string
     *
     * @param string $string The JSON API string
     *
     * @throws Art4\JsonApiClient\Exception\ValidationException If $string is not valid JSON API
     *
     * @return Art4\JsonApiClient\Document
     */
    public function parse($string);
}
