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

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Link Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
final class Link implements LinkInterface
{
	use AccessTrait;

	/**
	 * @var AccessInterface
	 */
	protected $parent;

	/**
	 * @var DataContainerInterface
	 */
	protected $container;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * Sets the manager and parent
	 *
	 * @param FactoryManagerInterface $manager The manager
	 * @param AccessInterface $parent The parent
	 */
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
	{
		$this->parent = $parent;

		$this->manager = $manager;

		$this->container = new DataContainer();
	}

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
		if ( ! is_object($object) )
		{
			throw new ValidationException('Link has to be an object or string, "' . gettype($object) . '" given.');
		}

		if ( ! array_key_exists('href', $object) )
		{
			throw new ValidationException('Link must have a "href" attribute.');
		}

		foreach (get_object_vars($object) as $name => $value)
		{
			$this->set($name, $value);
		}

		return $this;
	}

	/**
	 * Get a value by the key of this object
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		try
		{
			return $this->container->get($key);
		}
		catch (AccessException $e)
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
		}
	}

	/**
	 * Set a link
	 *
	 * @param string $name The Name
	 * @param string|object $link The Link
	 *
	 * @return self
	 */
	protected function set($name, $link)
	{
		if ( $name === 'meta' )
		{
			$meta = $this->manager->getFactory()->make(
				'Meta',
				[$this->manager, $this]
			);
			$meta->parse($link);

			$this->container->set($name, $meta);

			return $this;
		}

		// every link must be an URL
		if ( ! is_string($link) )
		{
			throw new ValidationException('Every link attribute has to be a string, "' . gettype($link) . '" given.');
		}

		$this->container->set($name, strval($link));

		return $this;
	}
}
