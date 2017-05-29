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
 * Relationship Link Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 *
 * links: a links object containing at least one of the following:
 * - self: a link for the relationship itself (a "relationship link"). This link allows
 *   the client to directly manipulate the relationship. For example, it would allow a
 *   client to remove an author from an article without deleting the people resource itself.
 * - related: a related resource link
 *
 * A relationship object that represents a to-many relationship MAY also contain pagination
 * links under the links member, as described below.
 */
final class RelationshipLink implements RelationshipLinkInterface
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
			throw new ValidationException('RelationshipLink has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'self') and ! property_exists($object, 'related') )
		{
			throw new ValidationException('RelationshipLink has to be at least a "self" or "related" link');
		}

		$links = get_object_vars($object);

		if ( array_key_exists('self', $links) )
		{
			if ( ! is_string($links['self']) )
			{
				throw new ValidationException('property "self" has to be a string, "' . gettype($links['self']) . '" given.');
			}

			$this->container->set('self', strval($links['self']));

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

		// Pagination links
		if ( $this->parent->has('data') and $this->parent->get('data') instanceof ResourceIdentifierCollectionInterface )
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
		if ( ! is_string($value) and ! is_null($value) )
		{
			throw new ValidationException('property "' . $name . '" has to be a string or null, "' . gettype($value) . '" given.');
		}

		if ( ! is_null($value) )
		{
			$this->container->set($name, strval($value));
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
