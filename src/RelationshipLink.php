<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\FactoryManagerInterface;
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
class RelationshipLink extends Link implements RelationshipLinkInterface
{
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

		if ( property_exists($object, 'self') )
		{
			if ( ! is_string($object->self) )
			{
				throw new ValidationException('property "self" has to be a string, "' . gettype($object->self) . '" given.');
			}

			$this->set('self', $object->self);
		}

		if ( property_exists($object, 'related') )
		{
			if ( ! is_string($object->related) )
			{
				throw new ValidationException('property "related" has to be a string, "' . gettype($object->related) . '" given.');
			}

			$this->set('related', $object->related);
		}

		if ( property_exists($object, 'pagination') )
		{
			$this->set('pagination', $this->manager->getFactory()->make(
				'PaginationLink',
				[$object->pagination, $this->manager]
			));
		}
	}
}
