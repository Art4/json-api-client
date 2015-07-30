<?php

namespace Art4\JsonApiClient;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
class ResourceIdentifier
{
	protected $type = null;

	protected $id = null;

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
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'type') )
		{
			throw new \InvalidArgumentException('A resource object MUST contain a type');
		}

		if ( ! property_exists($object, 'id') )
		{
			throw new \InvalidArgumentException('A resource object MUST contain an id');
		}

		$this->type = (string) $object->type;
		$this->id = (string) $object->id;

		if ( property_exists($object, 'meta') )
		{
			$this->meta = $object->meta;
		}

		return $this;
	}

	/**
	 * Get the type
	 *
	 * @return string The type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get the id
	 *
	 * @return string The id
	 */
	public function getId()
	{
		return $this->id;
	}
}
