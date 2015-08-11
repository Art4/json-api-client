<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\PaginationLink;

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
class RelationshipLink extends Link
{
	/**
	 * @param object $object The link object
	 *
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'self') and ! property_exists($object, 'related') )
		{
			throw new \InvalidArgumentException('RelationshipLink has to be at least a "self" or "related" link');
		}

		if ( property_exists($object, 'self') )
		{
			if ( ! is_string($object->self) )
			{
				throw new \InvalidArgumentException('property "self" has to be a string, "' . gettype($object->self) . '" given.');
			}

			$this->set('self', $object->self);
		}

		if ( property_exists($object, 'related') )
		{
			if ( ! is_string($object->related) )
			{
				throw new \InvalidArgumentException('property "related" has to be a string, "' . gettype($object->related) . '" given.');
			}

			$this->set('related', $object->related);
		}

		if ( property_exists($object, 'pagination') )
		{
			$this->set('pagination', new PaginationLink($object->pagination));
		}
	}

	/**
	 * Check if pagination exists in this document
	 *
	 * @return bool true if pagination exists, false if not
	 */
	public function hasPagination()
	{
		return $this->has('pagination');
	}

	/**
	 * Get the pagination of this document
	 *
	 * @throws \RuntimeException If pagination wasn't set, you can't get it
	 *
	 * @return PaginationLink The pagination
	 */
	public function getPagination()
	{
		return $this->get('pagination');
	}
}
