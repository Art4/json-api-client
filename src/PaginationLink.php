<?php

namespace Art4\JsonApiClient;

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
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		return parent::__construct($object);

		// #TODO
	}
}
