<?php

namespace Art4\JsonApiClient\Utils;

/**
 * Trait for array conversion
 */
trait AccessTrait
{
	/**
	 * Check if a value exists in this document
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		return $this->container->has($key);
	}

	/**
	 * Returns the keys of all setted values in this document
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		return $this->container->getKeys();
	}

	/**
	 * Convert this object in an array
	 *
	 * @param bool $fullArray If true, objects are transformed into arrays recursively
	 * @return array
	 */
	public function asArray($fullArray = false)
	{
		return $this->container->asArray($fullArray);
	}
}
