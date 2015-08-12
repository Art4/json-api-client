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
	 * Check if a value exists in this jsonapi object
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// version
		if ( $key === 'version' and $this->version !== null )
		{
			return true;
		}

		// meta
		if ( $key === 'meta' and $this->hasMeta() )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this object
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// version
		if ( $this->has('version') )
		{
			$keys[] = 'version';
		}

		// meta
		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		return $keys;
	}

	/**
	 * Get a value by the key
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in this jsonapi object.');
		}

		// meta
		if ( $key === 'meta' )
		{
			return $this->getMeta();
		}

		return $this->$key;
	}

	/**
	 * Check if version exists
	 *
	 * @return bool true if version exists, false if not
	 */
	public function hasVersion()
	{
		return $this->has('version');
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
		return $this->get('version');
	}
}
