<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Attributes Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-attributes
 */
class Attributes extends Meta implements AttributesInterface
{
	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * @param object $object The object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Attributes has to be an object, "' . gettype($object) . '" given.');
		}

		if ( property_exists($object, 'type') or property_exists($object, 'id') or property_exists($object, 'relationships') or property_exists($object, 'links') )
		{
			throw new ValidationException('These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`');
		}

		return parent::__construct($object, $manager);
	}
}
