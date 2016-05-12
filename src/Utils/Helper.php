<?php

namespace Art4\JsonApiClient\Utils;

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
	public static function parse($json_string)
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
	 * Checks if a string is a valid JSON API
	 *
	 * @param string $json_string
	 * @return bool true, if $json_string contains valid JSON API, else false
	 */
	public static function isValid($json_string)
	{
		try
		{
			$document = static::parse($json_string);
		}
		catch ( Exception $e )
		{
			return false;
		}

		return true;
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
