<?php

namespace Youthweb\JsonApiClient;

/**
 * PHP JSON API client
 *
 * Website: http://github.com/youthweb/json-api-client
 */
class Document
{
	protected $body = null;

	/**
	 * @param object $object The document body
	 *
	 * @return Document
	 *
	 * @throws \InvalidArgumentException
	 */
	public function parse($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		$this->body = $object;

		return $this;
	}
}
