<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Resource\ItemInterface;
use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Link Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
final class Link implements LinkInterface
{
	use AccessTrait;

	/**
	 * @var AccessInterface
	 */
	protected $parent;

	/**
	 * @var DataContainerInterface
	 */
	protected $container;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager, AccessInterface $parent)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Link has to be an object or string, "' . gettype($object) . '" given.');
		}

		if ( ! array_key_exists('href', $object) )
		{
			throw new ValidationException('Link must have a "href" attribute.');
		}

		$this->parent = $parent;

		$this->manager = $manager;

		$this->container = new DataContainer();

		foreach (get_object_vars($object) as $name => $value)
		{
			$this->set($name, $value);
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
			throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
		}
	}

	/**
	 * Set a link
	 *
	 * @param string $name The Name
	 * @param string|object $link The Link
	 *
	 * @return self
	 */
	protected function set($name, $link)
	{
		if ( $name === 'meta' )
		{
			$this->container->set($name, $this->manager->getFactory()->make(
				'Meta',
				[$link, $this->manager]
			));

			return $this;
		}

		// every link must be an URL
		if ( ! is_string($link) )
		{
			throw new ValidationException('Every link attribute has to be a string, "' . gettype($link) . '" given.');
		}

		$this->container->set($name, strval($link));

		return $this;
	}
}
