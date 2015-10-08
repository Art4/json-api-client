<?php

namespace Art4\JsonApiClient;

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
class ErrorLink extends Link implements ErrorLinkInterface
{
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

		$this->manager = $manager;

		$this->set('about', $object->about);
	}
}
