<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\PaginationLink;

/**
 * Document Link Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 *
 * The top-level links object MAY contain the following members:
 * - self: the link that generated the current response document.
 * - related: a related resource link when the primary data represents a resource relationship.
 * - pagination links for the primary data
 */
class DocumentLink extends Link
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
