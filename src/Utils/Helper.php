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

use Art4\JsonApiClient\Document;
use Art4\JsonApiClient\Exception\Exception;
use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Input\ResponseStringInput;

/**
 * PHP JSON API client helper
 *
 * Website: http://github.com/Art4/json-api-client
 */
final class Helper
{
    const JSONAPI_VERSION = '1.0';

    /**
     * @param string $jsonString
     *
     * @throws ValidationException
     *
     * @return Document
     */
    public static function parseResponseBody($jsonString)
    {
        $data = static::decodeJson($jsonString);

        $manager = new Manager();

        $document = $manager->getFactory()->make(
            'Document',
            [$manager]
        );
        $document->parse($data);

        return $document;
    }

    /**
     * @param string $jsonString
     *
     * @throws ValidationException
     *
     * @return Document
     */
    public static function parseRequestBody($jsonString)
    {
        $data = static::decodeJson($jsonString);

        $manager = new Manager();
        $manager->setConfig('optional_item_id', true);

        $document = $manager->getFactory()->make(
            'Document',
            [$manager]
        );
        $document->parse($data);

        return $document;
    }

    /**
     * @deprecated since version 0.9, to be removed in 1.0. Use parseResponseBody() instead
     *
     * @param string $jsonString
     *
     * @throws ValidationException
     *
     * @return Document
     */
    public static function parse($jsonString)
    {
        @trigger_error(__METHOD__ . ' is deprecated since version 0.9 and will be removed in 1.0. Use parseResponseBody() instead', E_USER_DEPRECATED);

        return static::parseResponseBody($jsonString);
    }

    /**
     * Checks if a string is a valid JSON API response body
     *
     * @param string $jsonString
     *
     * @return bool true, if $jsonString contains valid JSON API, else false
     */
    public static function isValidResponseBody($jsonString)
    {
        try {
            static::parseResponseBody($jsonString);
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
    public static function isValidRequestBody($jsonString)
    {
        try {
            static::parseRequestBody($jsonString);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Checks if a string is a valid JSON API
     *
     * @deprecated since version 0.9, to be removed in 1.0. Use isValidResponseBody() instead
     *
     * @param string $jsonString
     *
     * @return bool true, if $jsonString contains valid JSON API, else false
     */
    public static function isValid($jsonString)
    {
        @trigger_error(__METHOD__ . ' is deprecated since version 0.9 and will be removed in 1.0. Use isValidResponseBody() instead', E_USER_DEPRECATED);

        return static::isValidResponseBody($jsonString);
    }

    /**
     * Decodes a json string
     *
     * @deprecated since version 0.10, to be removed in 1.0. Use Art4\JsonApiClient\Input\ResponseStringInput::getAsObject() instead
     *
     * @param string $jsonString
     *
     * @throws ValidationException
     *
     * @return object
     */
    public static function decodeJson($jsonString)
    {
        @trigger_error(__METHOD__ . ' is deprecated since version 0.10 and will be removed in 1.0. Use Art4\JsonApiClient\Input\ResponseStringInput::getAsObject() instead', E_USER_DEPRECATED);

        try {
            return (new ResponseStringInput($jsonString))->getAsObject();
        } catch (InputException $e) {
            throw new ValidationException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
