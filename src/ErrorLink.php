<?php

namespace Art4\JsonApiClient;

/**
 * Error Link Object
 *
 * @see http://jsonapi.org/format/#error-objects
 *
 * An error object MAY have the following members:
 * - links: a links object containing the following members:
 *   - about: a link that leads to further details about this particular occurrence of the problem.
 */
class ErrorLink extends Link
{
	/**
	 * @param object $object The link object
	 *
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'about') )
		{
			throw new \InvalidArgumentException('$object MUST contain these properties: about');
		}

		$this->set('about', $object->about);
	}
}
