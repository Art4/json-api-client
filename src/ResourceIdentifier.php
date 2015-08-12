<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
class ResourceIdentifier
{
	use MetaTrait;

	protected $type = null;

	protected $id = null;

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

		if ( ! property_exists($object, 'type') )
		{
			throw new \InvalidArgumentException('A resource object MUST contain a type');
		}

		if ( ! property_exists($object, 'id') )
		{
			throw new \InvalidArgumentException('A resource object MUST contain an id');
		}

		if ( is_object($object->type) or is_array($object->type)  )
		{
			throw new \InvalidArgumentException('Resource type cannot be an array or object');
		}

		if ( is_object($object->id) or is_array($object->id)  )
		{
			throw new \InvalidArgumentException('Resource Id cannot be an array or object');
		}

		$this->type = strval($object->type);
		$this->id = strval($object->id);

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}

	/**
	 * Check if a value exists in this identifier
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// meta
		if ( $key === 'meta' and $this->hasMeta() )
		{
			return true;
		}

		// type always exists
		if ( $key === 'type' and $this->type !== null )
		{
			return true;
		}

		// id always exists
		if ( $key === 'id' and $this->id !== null )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this identifier
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// type
		if ( $this->has('type') )
		{
			$keys[] = 'type';
		}

		// id
		if ( $this->has('id') )
		{
			$keys[] = 'id';
		}

		// meta
		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this identifier
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in this identifier.');
		}

		if ( $key === 'meta' )
		{
			return $this->getMeta();
		}

		return $this->$key;
	}

	/**
	 * Convert this identifier in an array
	 *
	 * @return array
	 */
	public function asArray()
	{
		$return = array();

		foreach($this->getKeys() as $key)
		{
			$return[$key] = $this->get($key);
		}

		return $return;
	}
}
