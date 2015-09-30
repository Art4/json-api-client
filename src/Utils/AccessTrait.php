<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Exception\AccessException;

/**
 * Trait for array conversion
 */
trait AccessTrait
{
	/**
	 * Convert this object in an array
	 *
	 * @param bool $fullArray If true, objects are transformed into arrays recursively
	 * @return array
	 */
	public function asArray($fullArray = false)
	{
		$return = array();

		foreach($this->getKeys() as $key)
		{
			$val = $this->get($key);

			if ( $fullArray )
			{
				$return[$key] = $this->objectTransform($val);
			}
			else
			{
				$return[$key] = $val;
			}
		}

		return $return;
	}

	/**
	 * Check if a value exists
	 *
	 * @param mixed $key The key
	 * @return boolean
	 */
	public function has($key)
	{
		$key = $this->parseKey($key);

		$string = $key->shift();
		$key->next();

		if ( $key->count() === 0 )
		{
			return $this->hasValue($string);
		}

		if ( ! $this->hasValue($string) )
		{
			return false;
		}

		$value = $this->getValue($string);

		// #TODO Handle other objects an arrays
		if ( ! $value instanceof AccessInterface )
		{
			//throw new AccessException('The existance for the key "' . $key->raw . '" could\'nt be checked.');
			return false;
		}

		return $value->has($key);
	}

	/**
	 * Get a value by a key
	 *
	 * @param mixed $key The key
	 * @return mixed
	 */
	public function get($key)
	{
		$key = $this->parseKey($key);

		$string = $key->shift();
		$key->next();

		$value = $this->getValue($string);

		if ( $key->count() === 0 )
		{
			return $value;
		}

		if ( ! $value instanceof AccessInterface )
		{
			throw new AccessException('A value for the key "' . $key->raw . '" doesn\'t exist.');
		}

		return $value->get($key);
	}

	/**
	 * Parse a dot.notated.key to an object
	 *
	 * @param string|\SplStack $key The key
	 * @return \SplStack The parsed key
	 */
	protected function parseKey($key)
	{
		if ( is_object($key) and $key instanceof \SplStack )
		{
			return $key;
		}

		// Handle arrays and objects
		if ( is_object($key) or is_array($key) )
		{
			$key = '';
		}

		$key_string = strval($key);

		$keys = explode('.', $key_string);

		$key = new \SplStack;
		$key->raw = $key_string;

		foreach ( $keys as $value )
		{
			$key->push($value);
		}

		$key->rewind();

		return $key;
	}

	/**
	 * Transforms objects to arrays
	 *
	 * @param $val
	 * @return mixed
	 */
	protected function objectTransform($val)
	{
		if ( ! is_object($val) )
		{
			return $val;
		}
		elseif ( $val instanceOf AccessInterface )
		{
			return $val->asArray(true);
		}
		else
		{
			// Fallback for stdClass objects
			return json_decode(json_encode($val), true);
		}
	}
}
