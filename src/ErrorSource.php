<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessAbstract;
use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Source Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
class ErrorSource extends AccessAbstract
{
	use AccessTrait;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	protected $pointer = null;

	protected $parameter = null;

	/**
	 * @param object $object The error source object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('ErrorSource has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		if ( property_exists($object, 'pointer') )
		{
			if ( ! is_string($object->pointer) )
			{
				throw new ValidationException('property "pointer" has to be a string, "' . gettype($object->pointer) . '" given.');
			}

			$this->pointer = strval($object->pointer);
		}

		if ( property_exists($object, 'parameter') )
		{
			if ( ! is_string($object->parameter) )
			{
				throw new ValidationException('property "parameter" has to be a string, "' . gettype($object->parameter) . '" given.');
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
	protected function hasValue($key)
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
	protected function getValue($key)
	{
		if ( ! $this->has($key) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this error source.');
		}

		return $this->$key;
	}
}
