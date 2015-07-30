<?php

namespace Art4\JsonApiClient;

/**
 * JSON API Object
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 */
class Jsonapi
{
	protected $version = null;

	protected $meta = null;

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

		if ( property_exists($object, 'version') )
		{
			$this->version = (string) $object->version;
		}

		if ( property_exists($object, 'meta') )
		{
			$this->meta = new Meta($object->meta);
		}

		return $this;
	}
}
