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

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;

/**
 * Null Resource
 */
final class ResourceNull implements ResourceNullInterface
{
	/**
	 * Constructor need for mocking
	 *
	 * @param FactoryManagerInterface $manager The manager
	 * @param AccessInterface $parent The parent
	 */
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent) { }

	/**
	 * Parses the data for this element
	 *
	 * @param mixed $object The data
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function parse($object)
	{
		return $this;
	}

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
		throw new AccessException('A ResourceNull has no values.');
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
}
