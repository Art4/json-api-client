<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Exception\AccessException;

final class DataContainer implements DataContainerInterface
{
	/**
	 * @var array
	 */
	protected $allowed_keys = [];

	/**
	 * @var array
	 */
	protected $data = [];

	/**
	 * @param array $allowed_keys Keys of allowed values
	 */
	public function __construct(array $allowed_keys = [])
	{
		$this->allowed_keys = $allowed_keys;
	}

	/**
	 * Set a value
	 *
	 * @param string $key The Key
	 * @param mixed $value The Value
	 *
	 * @return self
	 */
	public function set($key, $value)
	{
		// Allow non-associative array for collections
		if ( $key === '' )
		{
			$this->data[] = $value;
		}
		else
		{
			$this->data[$key] = $value;
		}

		return $this;
	}

	/**
	 * Returns the keys of all setted values
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		return array_keys($this->data);
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
			return array_key_exists($string, $this->data);
		}

		if ( ! array_key_exists($string, $this->data) )
		{
			return false;
		}

		$value = $this->getValue($string);

		// #TODO Handle other objects and arrays
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

		// #TODO Handle other objects and arrays
		if ( ! $value instanceof AccessInterface )
		{
			throw new AccessException('Could not get the value for the key "' . $key->raw . '".');
		}

		return $value->get($key);
	}

	/**
	 * Convert this object in an array
	 *
	 * @param bool $fullArray If true, objects are transformed into arrays recursively
	 * @return array
	 */
	public function asArray($fullArray = false)
	{
		$array = array();

		foreach($this->getKeys() as $key)
		{
			$value = $this->getValue($key);

			if ( $fullArray )
			{
				$array[$key] = $this->objectTransform($value);
			}
			else
			{
				$array[$key] = $value;
			}
		}

		return $array;
	}

	/**
	 * Get a value by the key
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	protected function getValue($key)
	{
		if ( array_key_exists($key, $this->data) )
		{
			return $this->data[$key];
		}

		throw new AccessException('Could not get the value for the key "' . $key . '".');
	}

	/**
	 * Parse a dot.notated.key to an object
	 *
	 * @param string|AccessKey $key The key
	 * @return AccessKey The parsed key
	 */
	protected function parseKey($key)
	{
		if ( is_object($key) and $key instanceof AccessKey )
		{
			return $key;
		}

		// Handle arrays and objects
		if ( is_object($key) or is_array($key) )
		{
			$key = '';
		}

		$key_string = strval($key);

		$key = new AccessKey;
		$key->raw = $key_string;

		$keys = explode('.', $key_string);

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
