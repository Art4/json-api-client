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
			$this->attributes = new Attributes($object->attributes);
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

	/**
	 * Check if attributes exists in this resource
	 *
	 * @return bool true if data exists, false if not
	 */
	public function hasAttributes()
	{
		return $this->attributes !== null;
	}

	/**
	 * Get the attributes of this resource
	 *
	 * @throws \RuntimeException If attributes wasn't set, you can't get it
	 *
	 * @return Attributes The attributes object
	 */
	public function getAttributes()
	{
		if ( ! $this->hasAttributes() )
		{
			throw new \RuntimeException('You can\'t get "attributes", because it wasn\'t set.');
		}

		return $this->attributes;
	}
}
