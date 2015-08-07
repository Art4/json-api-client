<?php

namespace Art4\JsonApiClient;

/**
 * Attributes Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-attributes
 */
class Attributes extends Meta
{
	/**
	 * @param object $object The object
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

		if ( property_exists($object, 'type') or property_exists($object, 'id') or property_exists($object, 'relationships') or property_exists($object, 'links') )
		{
			throw new \InvalidArgumentException('These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`');
		}

		return parent::__construct($object);
	}
}
