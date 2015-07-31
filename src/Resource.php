<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\LinksTrait;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 */
class Resource extends ResourceIdentifier
{
	use LinksTrait;

	protected $attributes = null;

	protected $relationships = null;

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
			$this->setLinks(new Link($object->links));
		}

		return $this;
	}
}
