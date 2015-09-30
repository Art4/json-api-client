<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Meta Object
 *
 * @see http://jsonapi.org/format/#document-meta
 */
class Meta implements AccessInterface
{
	use AccessTrait;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	protected $_data = array();

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Meta has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

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
	 * @param string $key The Key
	 *
	 * @return mixed The value
	 */
	protected function getValue($key)
	{
		if ( ! $this->has($key) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
		}

		return $this->_data[$key];
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
