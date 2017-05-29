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
final class ResourceCollection implements ResourceCollectionInterface
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
		if ( ! is_array($object) )
		{
			throw new ValidationException('Resources for a collection has to be in an array, "' . gettype($object) . '" given.');
		}

		if ( count($object) > 0 )
		{
			foreach ($object as $resource)
			{
				$this->container->set('', $this->parseResource($resource));
			}
		}

		return $this;
	}

	/**
	 * Get a value by the key of this document
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

	/**
	 * Generate a new resource from an object
	 *
	 * @param object $data The resource data
	 * @return ElementInterface The resource
	 */
	protected function parseResource($data)
	{
		if ( ! is_object($data) )
		{
			throw new ValidationException('Resources inside a collection MUST be objects, "' . gettype($data) . '" given.');
		}

		$object_vars = get_object_vars($data);

		// the 2 properties must be type and id
		// or the 3 properties must be type, id and meta
		if ( count($object_vars) === 2 or ( count($object_vars) === 3 and property_exists($data, 'meta') ) )
		{
			$resource = $this->manager->getFactory()->make(
				'ResourceIdentifier',
				[$this->manager, $this]
			);
			$resource->parse($data);
		}
		else
		{
			$resource = $this->manager->getFactory()->make(
				'ResourceItem',
				[$this->manager, $this]
			);
			$resource->parse($data);
		}

		return $resource;
	}
}
