<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Relationship Collection Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 */
final class RelationshipCollection implements RelationshipCollectionInterface
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
	 * @param object $object The object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager, AccessInterface $resource)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Relationships has to be an object, "' . gettype($object) . '" given.');
		}

		if ( property_exists($object, 'type') or property_exists($object, 'id') )
		{
			throw new ValidationException('These properties are not allowed in attributes: `type`, `id`');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		$object_vars = get_object_vars($object);

		if ( count($object_vars) === 0 )
		{
			return $this;
		}

		foreach ($object_vars as $name => $value)
		{
			// #FIXME: Work here with parent strategy
			if ( $resource->has('attributes.' . $name) )
			{
				throw new ValidationException('"' . $name . '" property cannot be set because it exists already in parents Resource object.');
			}

			$this->container->set($name, $this->manager->getFactory()->make(
				'Relationship',
				[$value, $this->manager]
			));
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
			throw new AccessException('"' . $key . '" doesn\'t exist in this relationship collection.');
		}
	}
}
