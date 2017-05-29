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
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 */
final class ResourceItem implements ResourceItemInterface
{
	use AccessTrait;

	/**
	 * @var DataContainerInterface
	 */
	protected $container;

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
			throw new ValidationException('Resource has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'type') )
		{
			throw new ValidationException('A resource object MUST contain a type');
		}

		if ( is_object($object->type) or is_array($object->type)  )
		{
			throw new ValidationException('Resource type cannot be an array or object');
		}

		$this->container->set('type', strval($object->type));

		if ( $this->manager->getConfig('optional_item_id') === false or ! $this->parent instanceOf DocumentInterface )
		{
			if ( ! property_exists($object, 'id') )
			{
				throw new ValidationException('A resource object MUST contain an id');
			}

			if ( is_object($object->id) or is_array($object->id)  )
			{
				throw new ValidationException('Resource id cannot be an array or object');
			}

			$this->container->set('id', strval($object->id));
		}


		if ( property_exists($object, 'meta') )
		{
			$meta = $this->manager->getFactory()->make(
				'Meta',
				[$this->manager, $this]
			);
			$meta->parse($object->meta);

			$this->container->set('meta', $meta);
		}

		if ( property_exists($object, 'attributes') )
		{
			$attributes = $this->manager->getFactory()->make(
				'Attributes',
				[$this->manager]
			);
			$attributes->parse($object->attributes);

			$this->container->set('attributes', $attributes);
		}

		if ( property_exists($object, 'relationships') )
		{
			$relationships = $this->manager->getFactory()->make(
				'RelationshipCollection',
				[$this->manager, $this]
			);
			$relationships->parse($object->relationships);

			$this->container->set('relationships', $relationships);
		}

		if ( property_exists($object, 'links') )
		{
			$link = $this->manager->getFactory()->make(
				'ResourceItemLink',
				[$this->manager, $this]
			);
			$link->parse($object->links);

			$this->container->set('links', $link);
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
			throw new AccessException('"' . $key . '" doesn\'t exist in this resource.');
		}
	}
}
