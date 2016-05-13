<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Relationship Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 */
final class Relationship implements RelationshipInterface
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
	 * Sets the manager and parent
	 *
	 * @param FactoryManagerInterface $manager The manager
	 * @param AccessInterface $parent The parent
	 */
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent)
	{
		$this->manager = $manager;

		$this->container = new DataContainer();
	}

	/**
	 * Parses the data for this element
	 *
	 * @param mixed $object The data
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function parse($object)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Relationship has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'links') and ! property_exists($object, 'data') and ! property_exists($object, 'meta') )
		{
			throw new ValidationException('A Relationship object MUST contain at least one of the following properties: links, data, meta');
		}

		if ( property_exists($object, 'data') )
		{
			$this->container->set('data', $this->parseData($object->data));
		}

		if ( property_exists($object, 'meta') )
		{
			$meta = $this->manager->getFactory()->make(
				'Meta',
				[$this->manager, $this]
			);
			$meta->parse($object->meta);

			$this->container->set('meta', $meta);
		}

		// Parse 'links' after 'data'
		if ( property_exists($object, 'links') )
		{
			$link = $this->manager->getFactory()->make(
				'RelationshipLink',
				[$this->manager, $this]
			);
			$link->parse($object->links);

			$this->container->set('links', $link);
		}

		return $this;
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
			throw new AccessException('"' . $key . '" doesn\'t exist in Relationship.');
		}
	}

	/**
	 * Parse the data value
	 *
	 * @throws ValidationException If $data isn't null or an object
	 *
	 * @param null|array|object $data Data value
	 * @return null|Resource\Identifier|Resource\IdentifierCollection The parsed data
	 */
	protected function parseData($data)
	{
		if ( $data === null )
		{
			return $data;
		}

		if ( is_array($data) )
		{
			$collection = $this->manager->getFactory()->make(
				'Resource\IdentifierCollection',
				[$this->manager, $this]
			);
			$collection->parse($data);

			return $collection;
		}

		$identifier = $this->manager->getFactory()->make(
			'Resource\Identifier',
			[$this->manager, $this]
		);
		$identifier->parse($data);

		return $identifier;
	}
}
