<?php

namespace Art4\JsonApiClient;

/**
 * PHP JSON API client
 *
 * Website: http://github.com/Art4/json-api-client
 */
class Client
{
	const JSONAPI_VERSION = '1.0';

	/**
	 * @param string $json_string
	 *
	 * @return Document
	 *
	 * @throws \InvalidArgumentException
	 */
	public static function parse($json_string)
	{
		$jsonErrors = array(
			JSON_ERROR_DEPTH => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
			JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
			JSON_ERROR_CTRL_CHAR => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
			JSON_ERROR_SYNTAX => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
			JSON_ERROR_UTF8 => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded'
		);

		$data = json_decode($json_string, false, 512, JSON_BIGINT_AS_STRING);

		if ( json_last_error() !== JSON_ERROR_NONE )
		{
			$last = json_last_error();

			$error = 'Unknown error';

			if (isset($jsonErrors[$last]))
			{
				$error = $jsonErrors[$last];
			}

			throw new \InvalidArgumentException('Unable to parse JSON data: ' . $error);
		}

		return new Document($data);
	}
}
