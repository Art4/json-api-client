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

/**
 * Trait for array conversion
 */
trait AccessTrait
{
	/**
	 * Check if a value exists in this object
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		return $this->container->has($key);
	}

	/**
	 * Returns the keys of all setted values in this object
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
