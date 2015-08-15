<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\PaginationLink;
use Art4\JsonApiClient\Exception\ValidationException;

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
	 * @throws ValidationException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('DocumentLinks has to be an object, "' . gettype($object) . '" given.');
		}

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
			$this->set('pagination', new PaginationLink($object->pagination));
		}
	}
}
