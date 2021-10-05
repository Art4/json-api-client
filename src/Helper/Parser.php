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
     * @throws \Art4\JsonApiClient\Exception\InputException if something went wrong with the input
     * @throws \Art4\JsonApiClient\Exception\ValidationException If $jsonString contains invalid JSON API
     */
    public static function parseResponseString(string $jsonString): Accessable
    {
        $manager = new ErrorAbortManager(new Factory());

        return $manager->parse(new ResponseStringInput($jsonString));
    }

    /**
     * @throws \Art4\JsonApiClient\Exception\InputException if something went wrong with the input
     * @throws \Art4\JsonApiClient\Exception\ValidationException If $jsonString contains invalid JSON API
     */
    public static function parseRequestString(string $jsonString): Accessable
    {
        $manager = new ErrorAbortManager(new Factory());

        return $manager->parse(new RequestStringInput($jsonString));
    }

    /**
     * Checks if a string is a valid JSON API response body
     */
    public static function isValidResponseString(string $jsonString): bool
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
     */
    public static function isValidRequestString(string $jsonString): bool
    {
        try {
            static::parseRequestString($jsonString);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
