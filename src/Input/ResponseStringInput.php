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

namespace Art4\JsonApiClient\Input;

/**
 * Handles a http Response body as string
 */
final class ResponseStringInput implements Input
{
    use StringInputTrait;

    private string $rawString;

    /**
     * Set the input
     *
     * @param string $string
     *
     * @throws \Art4\JsonApiClient\Exception\InputException if $string is not a string
     */
    public function __construct($string)
    {
        $this->rawString = $this->prepareString($string);
    }

    /**
     * Get the input as simple object
     *
     * This should be a native PH stdClass object, so Manager could
     * iterate over all public attributes
     *
     * @throws \Art4\JsonApiClient\Exception\InputException if something went wrong with the input
     *
     * @return \stdClass
     */
    public function getAsObject()
    {
        return $this->decodeJson($this->rawString);
    }
}
