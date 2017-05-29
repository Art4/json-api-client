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
 * Error Link Object
 *
 * @see http://jsonapi.org/format/#error-objects
 *
 * An error object MAY have the following members:
 * - links: a links object containing the following members:
 *   - about: a link that leads to further details about this particular occurrence of the problem.
 */
final class ErrorLink implements ErrorLinkInterface
{
	use AccessTrait;

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
			throw new ValidationException('Link has to be an object, "' . gettype($object) . '" given.');
		}

		$links = get_object_vars($object);

		if ( ! array_key_exists('about', $links) )
		{
			throw new ValidationException('ErrorLink MUST contain these properties: about');
		}

		if ( ! is_string($links['about']) and ! is_object($links['about']) )
		{
			throw new ValidationException('Link has to be an object or string, "' . gettype($links['about']) . '" given.');
		}

		if ( is_string($links['about']) )
		{
			$this->container->set('about', strval($links['about']));
		}
		else
		{
			$link = $this->manager->getFactory()->make(
				'Link',
				[$this->manager, $this]
			);
			$link->parse($links['about']);

			$this->container->set('about', $link);
		}

		unset($links['about']);

		// custom links
		foreach ($links as $name => $value)
		{
			$this->setLink($name, $value);
		}
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
	 * @param string $name The name of the link
	 * @param string $link The link
	 * @return self
	 */
	private function setLink($name, $link)
	{
		if ( ! is_string($link) and ! is_object($link) )
		{
			throw new ValidationException('Link attribute has to be an object or string, "' . gettype($link) . '" given.');
		}

		if ( is_string($link) )
		{
			$this->container->set($name, strval($link));

			return $this;
		}

		// Now $link can only be an object
		$link_object = $this->manager->getFactory()->make(
			'Link',
			[$this->manager, $this]
		);
		$link_object->parse($link);

		$this->container->set($name, $link_object);

		return $this;
	}
}
