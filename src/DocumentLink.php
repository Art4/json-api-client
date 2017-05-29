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
 * Document Link Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 *
 * The top-level links object MAY contain the following members:
 * - self: the link that generated the current response document.
 * - related: a related resource link when the primary data represents a resource relationship.
 * - pagination links for the primary data
 */
final class DocumentLink implements DocumentLinkInterface
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
	 * @var AccessInterface
	 */
	protected $parent;

	/**
	 * Sets the manager and parent
	 *
	 * @param FactoryManagerInterface $manager The manager
	 * @param AccessInterface $parent The parent
	 */
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
	{
		$this->manager = $manager;

		$this->parent = $parent;

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
			throw new ValidationException('DocumentLink has to be an object, "' . gettype($object) . '" given.');
		}

		$links = get_object_vars($object);

		if ( array_key_exists('self', $links) )
		{
			if ( ! is_string($links['self']) and ! is_object($links['self']) )
			{
				throw new ValidationException('property "self" has to be a string or object, "' . gettype($links['self']) . '" given.');
			}

			$this->setLink('self', $links['self']);

			unset($links['self']);
		}

		if ( array_key_exists('related', $links) )
		{
			if ( ! is_string($links['related']) and ! is_object($links['related']) )
			{
				throw new ValidationException('property "related" has to be a string or object, "' . gettype($links['related']) . '" given.');
			}

			$this->setLink('related', $links['related']);

			unset($links['related']);
		}

		// Pagination links, if data in parent attributes exists
		if ( $this->parent->has('data') )
		{
			if ( array_key_exists('first', $links) )
			{
				$this->setPaginationLink('first', $links['first']);

				unset($links['first']);
			}

			if ( array_key_exists('last', $links) )
			{
				$this->setPaginationLink('last', $links['last']);

				unset($links['last']);
			}

			if ( array_key_exists('prev', $links) )
			{
				$this->setPaginationLink('prev', $links['prev']);

				unset($links['prev']);
			}

			if ( array_key_exists('next', $links) )
			{
				$this->setPaginationLink('next', $links['next']);

				unset($links['next']);
			}
		}

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
	 * Set a pagination link
	 *
	 * @param string $name The name of the link
	 * @param string $value The link
	 * @return self
	 */
	private function setPaginationLink($name, $value)
	{
		if ( ! is_object($value) and ! is_string($value) and ! is_null($value) )
		{
			throw new ValidationException('property "' . $name . '" has to be an object, a string or null, "' . gettype($value) . '" given.');
		}

		if ( is_string($value) )
		{
			$this->container->set($name, strval($value));
		}
		elseif ( is_object($value) )
		{
			$this->setLink($name, $value);
		}

		return $this;
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
