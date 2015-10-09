<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Pagination Link Object
 *
 * @see http://jsonapi.org/format/#fetching-pagination
 */
final class Pagination implements PaginationInterface
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
			throw new ValidationException('Pagination has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		if ( property_exists($object, 'first') )
		{
			if ( ! is_string($object->first) and ! is_null($object->first) )
			{
				throw new ValidationException('property "first" has to be a string or null, "' . gettype($object->first) . '" given.');
			}

			$this->set('first', $object->first);
		}

		if ( property_exists($object, 'last') )
		{
			if ( ! is_string($object->last) and ! is_null($object->last) )
			{
				throw new ValidationException('property "last" has to be a string or null, "' . gettype($object->last) . '" given.');
			}

			$this->set('last', $object->last);

		}

		if ( property_exists($object, 'prev') )
		{
			if ( ! is_string($object->prev) and ! is_null($object->prev) )
			{
				throw new ValidationException('property "prev" has to be a string or null, "' . gettype($object->prev) . '" given.');
			}

			$this->set('prev', $object->prev);

		}

		if ( property_exists($object, 'next') )
		{
			if ( ! is_string($object->next) and ! is_null($object->next) )
			{
				throw new ValidationException('property "next" has to be a string or null, "' . gettype($object->next) . '" given.');
			}

			$this->set('next', $object->next);

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

	/**
	 * Set a link
	 *
	 * @param string $name The Name
	 * @param string $link The Link
	 *
	 * @return self
	 */
	protected function set($name, $link)
	{
		// Pagination: Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
		if ( ! is_null($link) )
		{
			$this->container->set($name, strval($link));
		}

		return $this;
	}
}
