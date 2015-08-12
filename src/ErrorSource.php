<?php

namespace Art4\JsonApiClient;

/**
 * Error Source Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
class ErrorSource
{
	protected $pointer = null;

	protected $parameter = null;

	/**
	 * @param object $object The error source object
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

		if ( property_exists($object, 'pointer') )
		{
			if ( ! is_string($object->pointer) )
			{
				throw new \InvalidArgumentException('property "pointer" has to be a string, "' . gettype($object->pointer) . '" given.');
			}

			$this->pointer = strval($object->pointer);
		}

		if ( property_exists($object, 'parameter') )
		{
			if ( ! is_string($object->parameter) )
			{
				throw new \InvalidArgumentException('property "parameter" has to be a string, "' . gettype($object->parameter) . '" given.');
			}

			$this->parameter = strval($object->parameter);
		}

		return $this;
	}

	/**
	 * Check if a value exists in this error source
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// pointer
		if ( $key === 'pointer' and $this->pointer !== null )
		{
			return true;
		}

		// parameter
		if ( $key === 'parameter' and $this->parameter !== null )
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

		// pointer
		if ( $this->has('pointer') )
		{
			$keys[] = 'pointer';
		}

		// parameter
		if ( $this->has('parameter') )
		{
			$keys[] = 'parameter';
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this object
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in this error source.');
		}

		return $this->$key;
	}
}
