<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Error Source Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
final class ErrorSource implements ErrorSourceInterface
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
			throw new ValidationException('ErrorSource has to be an object, "' . gettype($object) . '" given.');
		}

		if ( property_exists($object, 'pointer') )
		{
			if ( ! is_string($object->pointer) )
			{
				throw new ValidationException('property "pointer" has to be a string, "' . gettype($object->pointer) . '" given.');
			}

			$this->container->set('pointer', strval($object->pointer));
		}

		if ( property_exists($object, 'parameter') )
		{
			if ( ! is_string($object->parameter) )
			{
				throw new ValidationException('property "parameter" has to be a string, "' . gettype($object->parameter) . '" given.');
			}

			$this->container->set('parameter', strval($object->parameter));
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
			throw new AccessException('"' . $key . '" doesn\'t exist in this error source.');
		}
	}
}
