<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;

/**
 * JSON API Object
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 */
class Jsonapi
{
	use MetaTrait;

	protected $version = null;

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
			if ( is_object($object->version) or is_array($object->version) )
			{
				throw new \InvalidArgumentException('property "version" cannot be an object or array, "' . gettype($object->version) . '" given.');
			}

			$this->version = strval($object->version);
		}

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}

	/**
	 * Check if version exists
	 *
	 * @return bool true if version exists, false if not
	 */
	public function hasVersion()
	{
		return $this->version !== null;
	}

	/**
	 * Get the version
	 *
	 * @throws \RuntimeException If pagination wasn't set, you can't get it
	 *
	 * @return PaginationLink The pagination
	 */
	public function getVersion()
	{
		if ( ! $this->hasVersion() )
		{
			throw new \RuntimeException('You can\'t get "version", because it wasn\'t set.');
		}

		return $this->version;
	}
}
