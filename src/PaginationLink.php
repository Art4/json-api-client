<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Pagination Link Object
 *
 * @see http://jsonapi.org/format/#fetching-pagination
 */
class PaginationLink extends Link
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
			throw new ValidationException('Pagination has to be an object, "' . gettype($object) . '" given.');
		}

		if ( property_exists($object, 'first') )
		{
			if ( ! is_string($object->first) and ! is_null($object->first) )
			{
				throw new ValidationException('property "first" has to be a string or null, "' . gettype($object->first) . '" given.');
			}

			$this->set('first', $object->first);
		}

		if ( property_exists($object, 'last') )
		{
			if ( ! is_string($object->last) and ! is_null($object->last) )
			{
				throw new ValidationException('property "last" has to be a string or null, "' . gettype($object->last) . '" given.');
			}

			$this->set('last', $object->last);
		}

		if ( property_exists($object, 'prev') )
		{
			if ( ! is_string($object->prev) and ! is_null($object->prev) )
			{
				throw new ValidationException('property "prev" has to be a string or null, "' . gettype($object->prev) . '" given.');
			}

			$this->set('prev', $object->prev);
		}

		if ( property_exists($object, 'next') )
		{
			if ( ! is_string($object->next) and ! is_null($object->next) )
			{
				throw new ValidationException('property "next" has to be a string or null, "' . gettype($object->next) . '" given.');
			}

			$this->set('next', $object->next);
		}
	}
}
