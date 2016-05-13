<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2016  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\Exception\AccessException;

/**
 * Null Resource
 */
final class NullResource implements NullResourceInterface, ResourceInterface
{
	/**
	 * Constructor need for mocking
	 */
	public function __construct() { }

	/**
	 * Check if a value exists in this resource
	 *
	 * @param string $key The key of the value
	 * @return bool false
	 */
	public function has($key)
	{
		return false;
	}

	/**
	 * Returns the keys of all setted values in this resource
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		return array();
	}

	/**
	 * Get a value by the key of this identifier
	 *
	 * @param string $key The key of the value
	 */
	public function get($key)
	{
		throw new AccessException('A NullResource has no values.');
	}

	/**
	 * Convert this object in an array
	 *
	 * @return null
	 */
	public function asArray()
	{
		// Null can't converted into an array, because it has no keys
		return null;
	}

	/**
	 * Is this Resource a null resource?
	 *
	 * @return boolean true
	 */
	public function isNull()
	{
		return true;
	}

	/**
	 * Is this Resource an identifier?
	 *
	 * @return boolean false
	 */
	public function isIdentifier()
	{
		return false;
	}

	/**
	 * Is this Resource an item?
	 *
	 * @return boolean false
	 */
	public function isItem()
	{
		return false;
	}

	/**
	 * Is this Resource a collection?
	 *
	 * @return boolean true
	 */
	public function isCollection()
	{
		return false;
	}
}
