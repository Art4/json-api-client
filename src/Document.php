<?php

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
			$this->container->set('meta', $this->manager->getFactory()->make(
				'Meta',
				[$object->meta, $this->manager]
			));
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

			$this->container->set('included', $this->manager->getFactory()->make(
				'Resource\Collection',
				[$object->included, $this->manager]
			));
		}

		if ( property_exists($object, 'jsonapi') )
		{
			$this->container->set('jsonapi', $this->manager->getFactory()->make(
				'Jsonapi',
				[$object->jsonapi, $this->manager]
			));
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
	 * @return ResourceInterface The parsed data
	 */
	protected function parseData($data)
	{
		if ( $data === null )
		{
			return $this->manager->getFactory()->make(
				'Resource\NullResource',
				[$data, $this->manager]
			);
		}

		if ( is_array($data) )
		{
			return $this->manager->getFactory()->make(
				'Resource\Collection',
				[$data, $this->manager]
			);
		}

		if ( ! is_object($data) )
		{
			throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
		}

		$object_vars = get_object_vars($data);

		// the properties must be type and id
		if ( count($object_vars) === 2 )
		{
			$resource = $this->manager->getFactory()->make(
				'Resource\Identifier',
				[$data, $this->manager]
			);
		}
		// the 3 properties must be type, id and meta
		elseif ( count($object_vars) === 3 and property_exists($data, 'meta') )
		{
			$resource = $this->manager->getFactory()->make(
				'Resource\Identifier',
				[$data, $this->manager]
			);
		}
		else
		{
			$resource = $this->manager->getFactory()->make(
				'Resource\Item',
				[$data, $this->manager]
			);
		}

		return $resource;
	}
}
