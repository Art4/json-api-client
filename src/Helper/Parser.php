<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

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
