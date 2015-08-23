<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Resource\ResourceInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Relationship Collection Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 */
class RelationshipCollection
{
	protected $_data = array();

	/**
	 * @param object $object The object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, ResourceInterface $resource)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Relationships has to be an object, "' . gettype($object) . '" given.');
		}

		if ( property_exists($object, 'type') or property_exists($object, 'id') )
		{
			throw new ValidationException('These properties are not allowed in attributes: `type`, `id`');
		}

		$object_vars = get_object_vars($object);

		if ( count($object_vars) === 0 )
		{
			return $this;
		}

		foreach ($object_vars as $name => $value)
		{
			if ( $resource->has('attributes') and $resource->get('attributes')->has($name) )
			{
				throw new ValidationException('"' . $name . '" property cannot be set because it exists already in parents Resource object.');
			}

			$this->set($name, new Relationship($value));
		}

		return $this;
	}

	/**
	 * Is a value set?
	 *
	 * @param string $key The Key
	 *
	 * @return bool true if the value is set, false if not
	 */
	public function has($key)
	{
		return array_key_exists($key, $this->_data);
	}

	/**
	 * Returns the keys of all setted values
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		return array_keys($this->_data);
	}

	/**
	 * Get a value
	 *
	 * @param string $name The Name
	 *
	 * @return mixed The value
	 */
	public function get($name)
	{
		if ( ! $this->has($name) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this relationship collection.');
		}

		return $this->_data[$name];
	}

	/**
	 * Set a value
	 *
	 * @param string $name The Name
	 * @param mixed $value The Value
	 *
	 * @return self
	 */
	protected function set($name, Relationship $value)
	{
		$this->_data[$name] = $value;

		return $this;
	}
}
