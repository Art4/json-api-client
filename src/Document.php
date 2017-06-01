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
 * Document Top Level Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 */
final class Document implements DocumentInterface
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
	 * @param object $object The document body
	 *
	 * @return Document
	 *
	 * @throws ValidationException
	 */
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent = null)
	{
		$this->manager = $manager;

		$this->container = new DataContainer();
	}

	/**
	 * @param object $object The document body
	 *
	 * @return Document
	 *
	 * @throws ValidationException
	 */
	public function parse($object)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Document has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'data') and ! property_exists($object, 'meta') and ! property_exists($object, 'errors') )
		{
			throw new ValidationException('Document MUST contain at least one of the following properties: data, errors, meta');
		}

		if ( property_exists($object, 'data') and property_exists($object, 'errors') )
		{
			throw new ValidationException('The properties `data` and `errors` MUST NOT coexist in Document.');
		}

		if ( property_exists($object, 'data') )
		{
			$this->container->set('data', $this->parseData($object->data));
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

		if ( property_exists($object, 'errors') )
		{
			$errors = $this->manager->getFactory()->make(
				'ErrorCollection',
				[$this->manager, $this]
			);
			$errors->parse($object->errors);

			$this->container->set('errors', $errors);
		}

		if ( property_exists($object, 'included') )
		{
			if ( ! property_exists($object, 'data') )
			{
				throw new ValidationException('If Document does not contain a `data` property, the `included` property MUST NOT be present either.');
			}

			$collection = $this->manager->getFactory()->make(
				'ResourceCollection',
				[$this->manager, $this]
			);
			$collection->parse($object->included);

			$this->container->set('included', $collection);
		}

		if ( property_exists($object, 'jsonapi') )
		{
			$jsonapi = $this->manager->getFactory()->make(
				'Jsonapi',
				[$this->manager, $this]
			);
			$jsonapi->parse($object->jsonapi);

			$this->container->set('jsonapi', $jsonapi);
		}

		if ( property_exists($object, 'links') )
		{
			$links = $this->manager->getFactory()->make(
				'DocumentLink',
				[$this->manager, $this]
			);
			$links->parse($object->links);

			$this->container->set('links', $links);
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
			throw new AccessException('"' . $key . '" doesn\'t exist in Document.');
		}
	}

	/**
	 * Parse the data value
	 *
	 * @throws ValidationException If $data isn't null or an object
	 *
	 * @param null|object $data Data value
	 * @return ElementInterface The parsed data
	 */
	protected function parseData($data)
	{
		if ( $data === null )
		{
			$resource = $this->manager->getFactory()->make(
				'ResourceNull',
				[$this->manager, $this]
			);
			$resource->parse($data);

			return $resource;
		}

		if ( is_array($data) )
		{
			$collection =  $this->manager->getFactory()->make(
				'ResourceCollection',
				[$this->manager, $this]
			);
			$collection->parse($data);

			return $collection;
		}

		if ( ! is_object($data) )
		{
			throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
		}

		$object_keys = array_keys(get_object_vars($data));
		sort($object_keys);

		// the properties must be type and id or
		// the 3 properties must be type, id and meta
		if ( $object_keys === ['id', 'type'] or $object_keys === ['id', 'meta', 'type'] )
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
