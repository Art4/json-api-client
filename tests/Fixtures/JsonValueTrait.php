<?php

namespace Art4\JsonApiClient\Tests\Fixtures;

/**
 * Link Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
trait JsonValueTrait
{
	/**
	 * Json Values Provider
	 *
	 * @see http://json.org/
	 */
	public function jsonValuesProvider()
	{
		return array(
		array(new \stdClass()),
		array(array()),
		array('string'),
		array(456),
		array(true),
		array(false),
		array(null),
		);
	}
}
