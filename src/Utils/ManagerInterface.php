<?php

namespace Art4\JsonApiClient\Utils;

/**
 * Manager Interface
 */
interface ManagerInterface
{
	/**
	 * Parse a JSON API string
	 *
	 * @param  string $string The JSON API string
	 *
	 * @throws  Art4\JsonApiClient\Exception\ValidationException If $string is not valid JSON API
	 *
	 * @return Art4\JsonApiClient\Document
	 */
	public function parse($string);
}
