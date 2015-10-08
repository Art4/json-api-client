<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Collection Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
class ErrorCollection implements ErrorCollectionInterface
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
	 * @param array $resources The resources as array
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($errors, FactoryManagerInterface $manager)
	{
		if ( ! is_array($errors) )
		{
			throw new ValidationException('Errors for a collection has to be in an array, "' . gettype($errors) . '" given.');
		}

		if ( count($errors) === 0 )
		{
			throw new ValidationException('Errors array cannot be empty and MUST have at least one object');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		foreach ($errors as $error)
		{
			$this->container->set('', $this->manager->getFactory()->make(
				'Error',
				[$error, $this->manager]
			));
		}

		return $this;
	}

	/**
	 * Get a value by the key of this document
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
			throw new AccessException('"' . $key . '" doesn\'t exist in this collection.');
		}
	}
}
