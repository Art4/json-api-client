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

namespace Art4\JsonApiClient\Helper;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\Exception;
use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\V1\Factory;

/**
 * Parser for JSON API strings
 */
final class Parser
{
    /**
     * @param string $jsonString
     *
     * @throws Art4\JsonApiClient\Exception\Exception
     *
     * @return Art4\JsonApiClient\Accessable
     */
    public static function parseResponseString($jsonString)
    {
        $manager = new ErrorAbortManager(new Factory());

        return $manager->parse(new ResponseStringInput($jsonString));
    }

    /**
     * @param string $jsonString
     *
     * @throws Art4\JsonApiClient\Exception\Exception
     *
     * @return Art4\JsonApiClient\Accessable
     */
    public static function parseRequestString($jsonString)
    {
        $manager = new ErrorAbortManager(new Factory());

        return $manager->parse(new RequestStringInput($jsonString));
    }

    /**
     * Checks if a string is a valid JSON API response body
     *
     * @param string $jsonString
     *
     * @return bool true, if $jsonString contains valid JSON API, else false
     */
    public static function isValidResponseString($jsonString)
    {
        try {
            static::parseResponseString($jsonString);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Checks if a string is a valid JSON API request body
     *
     * @param string $jsonString
     *
     * @return bool true, if $jsonString contains valid JSON API, else false
     */
    public static function isValidRequestString($jsonString)
    {
        try {
            static::parseRequestString($jsonString);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
