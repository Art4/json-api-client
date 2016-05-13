<?php

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;

/**
 * Null Resource
 */
final class NullResource implements NullResourceInterface, ResourceInterface
{
	/**
	 * Constructor need for mocking
	 *
	 * @param FactoryManagerInterface $manager The manager
	 * @param AccessInterface $parent The parent
	 */
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent) { }

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
		return $this;
	}

	/**
	 * Check if a value exists in this resource
	 *
	 * @param string $key The key of the value
	 * @return bool false
	 */
	public function has($key)
	{
		return false;
	}

	/**
	 * Returns the keys of all setted values in this resource
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		return array();
	}

	/**
	 * Get a value by the key of this identifier
	 *
	 * @param string $key The key of the value
	 */
	public function get($key)
	{
		throw new AccessException('A NullResource has no values.');
	}

	/**
	 * Convert this object in an array
	 *
	 * @return null
	 */
	public function asArray()
	{
		// Null can't converted into an array, because it has no keys
		return null;
	}

	/**
	 * Is this Resource a null resource?
	 *
	 * @return boolean true
	 */
	public function isNull()
	{
		return true;
	}

	/**
	 * Is this Resource an identifier?
	 *
	 * @return boolean false
	 */
	public function isIdentifier()
	{
		return false;
	}

	/**
	 * Is this Resource an item?
	 *
	 * @return boolean false
	 */
	public function isItem()
	{
		return false;
	}

	/**
	 * Is this Resource a collection?
	 *
	 * @return boolean true
	 */
	public function isCollection()
	{
		return false;
	}
}
