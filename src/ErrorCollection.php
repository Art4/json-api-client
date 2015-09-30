<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Collection Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
class ErrorCollection implements AccessInterface
{
	use AccessTrait;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	protected $errors = array();

	/**
	 * @param array $resources The resources as array
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($errors, FactoryManagerInterface $manager)
	{
		if ( ! is_array($errors) )
		{
			throw new ValidationException('Errors for a collection has to be in an array, "' . gettype($errors) . '" given.');
		}

		if ( count($errors) === 0 )
		{
			throw new ValidationException('Errors array cannot be empty and MUST have at least one object');
		}

		$this->manager = $manager;

		foreach ($errors as $error)
		{
			$this->addError($this->manager->getFactory()->make(
				'Error',
				[$error, $this->manager]
			));
		}

		return $this;
	}

	/**
	 * Check if a value exists in this collection
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	protected function hasValue($key)
	{
		if ( is_object($key) or is_array($key) )
		{
			return false;
		}

		if ( is_string($key) and ! ctype_digit($key) )
		{
			return false;
		}

		$key = intval($key);

		if ( isset($this->errors[$key]) )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this collection
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		if ( count($this->errors) > 0 )
		{
			foreach ( $this->errors as $key => $value )
			{
				$keys[] = $key;
			}
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this collection
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	protected function getValue($key)
	{
		if ( ! $this->has($key) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this collection.');
		}

		return $this->errors[$key];
	}

	/**
	 * Add an error to this collection
	 *
	 * @param Error $error The Error
	 * @return self
	 */
	protected function addError(Error $error)
	{
		$this->errors[] = $error;

		return $this;
	}
}
