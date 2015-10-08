<?php

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
class Collection implements CollectionInterface, ResourceInterface
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
