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

use Art4\JsonApiClient\Exception\InputException;

/**
 * Contains helper methods for handling string inputs
 */
trait StringInputTrait
{
    /**
     * prepare the string
     *
     * @param string $string
     *
     * @throws InputException if $string is not a string
     *
     * @return string
     */
    public function prepareString($string)
    {
        if (! is_string($string)) {
            throw new InputException(sprintf(
                '$string must be a string, "%s" given.',
                gettype($string)
            ));
        }

        return $string;
    }

    /**
     * Decodes a json string
     *
     * @param string $jsonString
     *
     * @throws InputException if somethin went wrong with the input
     *
     * @return object
     */
    protected function decodeJson($jsonString)
    {
        $jsonErrors = [
            \JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
            \JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
            \JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
            \JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
            \JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded'
        ];

        // Can we use JSON_BIGINT_AS_STRING?
        $options = (version_compare(\PHP_VERSION, '5.4.0', '>=') and ! (defined('JSON_C_VERSION') and \PHP_INT_SIZE > 4)) ? \JSON_BIGINT_AS_STRING : 0;
        $data = json_decode($jsonString, false, 512, $options);

        if (json_last_error() !== \JSON_ERROR_NONE) {
            $last = json_last_error();

            $error = 'Unknown error';

            if (isset($jsonErrors[$last])) {
                $error = $jsonErrors[$last];
            }

            throw new InputException('Unable to parse JSON data: ' . $error);
        }

        return $data;
    }
}
