<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Link Object
 *
 * @see http://jsonapi.org/format/#error-objects
 *
 * An error object MAY have the following members:
 * - links: a links object containing the following members:
 *   - about: a link that leads to further details about this particular occurrence of the problem.
 */
class ErrorLink implements ErrorLinkInterface
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
			throw new ValidationException('Link has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'about') )
		{
			throw new ValidationException('ErrorLink MUST contain these properties: about');
		}

		if ( ! is_string($object->about) and ! is_object($object->about) )
		{
			throw new ValidationException('Link has to be an object or string, "' . gettype($link) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		if ( is_string($object->about) )
		{
			$this->container->set('about', strval($object->about));

			return $this;
		}

		$this->container->set('about', $this->manager->getFactory()->make(
			'Link',
			[$object->about, $this->manager]
		));
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
