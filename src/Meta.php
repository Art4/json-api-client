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

		$object_vars = get_object_vars($object);

		if ( count($object_vars) === 0 )
		{
			return $this;
		}

		foreach ($object_vars as $name => $value)
		{
			$this->set($name, $value);
		}

		return $this;
	}

	/**
	 * Is a value set?
	 *
	 * @param string $name The Name
	 *
	 * @return bool true if the value is set, false if not
	 */
	public function __isset($name)
	{
		return array_key_exists($name, $this->_data);
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
		if ( ! $this->__isset($name) )
		{
			throw new \RuntimeException('You can\'t get "' . $name . '", because it wasn\'t set.');
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
	protected function set($name, $value)
	{
		$this->_data[$name] = $value;

		return $this;
	}
}
