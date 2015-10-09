<?php

namespace Art4\JsonApiClient;

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
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Link has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		$object_vars = get_object_vars($object);

		if ( count($object_vars) === 0 )
		{
			return $this;
		}

		foreach ($object_vars as $name => $value)
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

		// from spec: an object ("link object") which can contain the following members:
		// - href: a string containing the link's URL.
		if ( $name === 'href' or ! is_object($link) )
		{
			if ( ! is_string($link) )
			{
				throw new ValidationException('Link has to be an object or string, "' . gettype($link) . '" given.');
			}

			$this->container->set($name, strval($link));

			return $this;
		}

		// Now $link can only be an object
		// Create Link object if needed
		if ( ! ($link instanceof LinkInterface) )
		{
			$this->container->set($name, $this->manager->getFactory()->make(
				'Link',
				[$link, $this->manager]
			));
		}

		return $this;
	}
}
