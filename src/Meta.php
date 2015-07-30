<?php

namespace Art4\JsonApiClient;

/**
 * Meta Object
 *
 * @see http://jsonapi.org/format/#document-meta
 */
class Meta
{
	protected $_data = array();

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		return $this;
	}
}
