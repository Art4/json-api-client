<?php

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
	 * @param object $object The link object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('RelationshipLink has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'self') and ! property_exists($object, 'related') )
		{
			throw new ValidationException('RelationshipLink has to be at least a "self" or "related" link');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		if ( property_exists($object, 'self') )
		{
			if ( ! is_string($object->self) )
			{
				throw new ValidationException('property "self" has to be a string, "' . gettype($object->self) . '" given.');
			}

			$this->container->set('self', strval($object->self));
		}

		if ( property_exists($object, 'related') )
		{
			if ( ! is_string($object->related) )
			{
				throw new ValidationException('property "related" has to be a string, "' . gettype($object->related) . '" given.');
			}

			$this->container->set('related', strval($object->related));
		}

		if ( property_exists($object, 'pagination') )
		{
			$this->container->set('pagination', $this->manager->getFactory()->make(
				'Pagination',
				[$object->pagination, $this->manager]
			));
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
}
