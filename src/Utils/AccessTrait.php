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
	 * Converts a key into an object
	 *
	 * @param mixed $key The key
	 * @return array
	 */
	public function get($key)
	{
		if ( ! is_object($key) and ! $key instanceof \SplStack )
		{
			// #TODO Handle arrays and objects
			$key_string = (string) $key;

			$keys = explode('.', $key_string);

			$key = new \SplStack;
			$key->raw = $key_string;

			foreach ( $keys as $value )
			{
				$key->push($value);
			}

			$key->rewind();
		}

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
