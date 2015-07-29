<?php

namespace Art4\JsonApiClient;

/**
 * Document Top Level Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 */
class Resource extends ResourceIdentifier
{
	protected $attributes = null;

	protected $relationships = null;

	protected $meta = null;

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		// check type, id and meta in ResourceIdentifier
		parent::__construct($object);

		if ( property_exists($object, 'attributes') )
		{
			$this->attributes = $object->attributes;
		}

		if ( property_exists($object, 'relationships') )
		{
			$this->relationships = $object->relationships;
		}

		if ( property_exists($object, 'links') )
		{
			$this->links = $object->links;
		}

		return $this;
	}
}
