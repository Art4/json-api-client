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
	 * Check if pointer exists
		*
	 * @return bool true if pointer exists, false if not
	 */
	public function hasPointer()
	{
		return $this->pointer !== false;
	}

	/**
	 * Get the pointer
	 *
	 * @throws \RuntimeException If pointer wasn't set, you can't get it
	 *
	 * @return string The pointer
	 */
	public function getPointer()
	{
		if ( ! $this->hasPointer() )
		{
			throw new \RuntimeException('You can\'t get "pointer", because it wasn\'t set.');
		}

		return $this->pointer;
	}

	/**
	 * Check if pointer exists
	 *
	 * @return bool true if pointer exists, false if not
	 */
	public function hasParameter()
	{
		return $this->parameter !== false;
	}

	/**
	 * Get the parameter
	 *
	 * @throws \RuntimeException If parameter wasn't set, you can't get it
	 *
	 * @return string The parameter
	 */
	public function getParameter()
	{
		if ( ! $this->hasParameter() )
		{
			throw new \RuntimeException('You can\'t get "parameter", because it wasn\'t set.');
		}

		return $this->parameter;
	}
}
