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
final class IdentifierCollection implements IdentifierCollectionInterface, ResourceInterface
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
	 * @param array $resources The resources as array
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($resources, FactoryManagerInterface $manager)
	{
		if ( ! is_array($resources) )
		{
			throw new ValidationException('Resources for a collection has to be in an array, "' . gettype($resources) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		if ( count($resources) > 0 )
		{
			foreach ($resources as $resource)
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
	 * @return ResourceInterface The resource
	 */
	protected function parseResource($data)
	{
		return $resource = $this->manager->getFactory()->make(
			'Resource\Identifier',
			[$data, $this->manager]
		);
	}

	/**
	 * Is this Resource a null resource?
	 *
	 * @return boolean false
	 */
	public function isNull()
	{
		return false;
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
		return true;
	}
}
