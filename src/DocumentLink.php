<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
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
class DocumentLink implements DocumentLinkInterface
{
	use AccessTrait;

	/**
	 * @var DataContainerInterface
	 */
	protected $container;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * @param object $object The link object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('DocumentLink has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		if ( property_exists($object, 'self') )
		{
			if ( ! is_string($object->self) )
			{
				throw new ValidationException('property "self" has to be a string, "' . gettype($object->self) . '" given.');
			}

			$this->container->set('self', $object->self);
		}

		if ( property_exists($object, 'related') )
		{
			if ( ! is_string($object->related) )
			{
				throw new ValidationException('property "related" has to be a string, "' . gettype($object->related) . '" given.');
			}

			$this->container->set('related', $object->related);
		}

		if ( property_exists($object, 'pagination') )
		{
			$this->container->set('pagination', $this->manager->getFactory()->make(
				'PaginationLink',
				[$object->pagination, $this->manager]
			));
		}
	}

	/**
	 * Get a value by the key of this object
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		try
		{
			return $this->container->get($key);
		}
		catch (AccessException $e)
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
		}
	}
}
