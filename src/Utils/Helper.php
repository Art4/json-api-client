<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

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
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * PHP JSON API client helper
 *
 * Website: http://github.com/Art4/json-api-client
 */
final class Helper
{
	const JSONAPI_VERSION = '1.0';

	/**
	 * @param string $json_string
	 *
	 * @return Document
	 *
	 * @throws ValidationException
	 */
	public static function parseResponseBody($json_string)
	{
		$data = static::decodeJson($json_string);

		$manager = new Manager();

		$document = $manager->getFactory()->make(
			'Document',
			[$manager]
		);
		$document->parse($data);

		return $document;
	}

	/**
	 * @param string $json_string
	 *
	 * @return Document
	 *
	 * @throws ValidationException
	 */
	public static function parseRequestBody($json_string)
	{
		$data = static::decodeJson($json_string);

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
	 *
	 * @deprecated since version 0.9, to be removed in 1.0. Use parseResponseBody() instead
	 *
	 * @param string $json_string
	 *
	 * @return Document
	 *
	 * @throws ValidationException
	 */
	public static function parse($json_string)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.9 and will be removed in 1.0. Use parseResponseBody() instead', E_USER_DEPRECATED);

		return static::parseResponseBody($json_string);
	}

	/**
	 * Checks if a string is a valid JSON API response body
	 *
	 * @param string $json_string
	 * @return bool true, if $json_string contains valid JSON API, else false
	 */
	public static function isValidResponseBody($json_string)
	{
		try
		{
			static::parseResponseBody($json_string);
		}
		catch ( Exception $e )
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks if a string is a valid JSON API request body
	 *
	 * @param string $json_string
	 * @return bool true, if $json_string contains valid JSON API, else false
	 */
	public static function isValidRequestBody($json_string)
	{
		try
		{
			static::parseRequestBody($json_string);
		}
		catch ( Exception $e )
		{
			return false;
		}

		return true;
	}

	/**
	 * Checks if a string is a valid JSON API
	 *
	 * @deprecated since version 0.9, to be removed in 1.0. Use isValidResponseBody() instead
	 *
	 * @param string $json_string
	 * @return bool true, if $json_string contains valid JSON API, else false
	 */
	public static function isValid($json_string)
	{
		@trigger_error(__METHOD__ . ' is deprecated since version 0.9 and will be removed in 1.0. Use parseResponseBody() instead', E_USER_DEPRECATED);

		return static::isValidResponseBody($json_string);
	}

	/**
	 * Decodes a json string
	 *
	 * @param string $json_string
	 *
	 * @return object
	 *
	 * @throws ValidationException
	 */
	public static function decodeJson($json_string)
	{
		$jsonErrors = array(
			JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
			JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
			JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
			JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
			JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded'
		);

		// Can we use JSON_BIGINT_AS_STRING?
		$options = ( version_compare(PHP_VERSION, '5.4.0', '>=') and ! (defined('JSON_C_VERSION') and PHP_INT_SIZE > 4) ) ? JSON_BIGINT_AS_STRING : 0;
		$data = json_decode($json_string, false, 512, $options);

		if ( json_last_error() !== JSON_ERROR_NONE )
		{
			$last = json_last_error();

			$error = 'Unknown error';

			if (isset($jsonErrors[$last]))
			{
				$error = $jsonErrors[$last];
			}

			throw new ValidationException('Unable to parse JSON data: ' . $error);
		}

		return $data;
	}
}
