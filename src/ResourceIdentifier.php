<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
class ResourceIdentifier
{
	use MetaTrait;

	protected $type = null;

	protected $id = null;

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

		if ( is_object($object->type) or is_array($object->type)  )
		{
			throw new \InvalidArgumentException('Resource type cannot be an array or object');
		}

		if ( is_object($object->id) or is_array($object->id)  )
		{
			throw new \InvalidArgumentException('Resource Id cannot be an array or object');
		}

		$this->type = strval($object->type);
		$this->id = strval($object->id);

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
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
