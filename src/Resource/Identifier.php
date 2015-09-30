<?php

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Identifier Object
 *
 * @see http://jsonapi.org/format/#document-resource-identifier-objects
 */
class Identifier implements AccessInterface, ResourceInterface
{
	use AccessTrait;

	use MetaTrait;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	protected $type = null;

	protected $id = null;

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
			throw new ValidationException('Resource has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'type') )
		{
			throw new ValidationException('A resource object MUST contain a type');
		}

		if ( ! property_exists($object, 'id') )
		{
			throw new ValidationException('A resource object MUST contain an id');
		}

		if ( is_object($object->type) or is_array($object->type)  )
		{
			throw new ValidationException('Resource type cannot be an array or object');
		}

		if ( is_object($object->id) or is_array($object->id)  )
		{
			throw new ValidationException('Resource Id cannot be an array or object');
		}

		$this->manager = $manager;

		$this->type = strval($object->type);
		$this->id = strval($object->id);

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}

	/**
	 * Check if a value exists in this identifier
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	protected function hasValue($key)
	{
		// meta
		if ( $key === 'meta' and $this->hasMeta() )
		{
			return true;
		}

		// type always exists
		if ( $key === 'type' and $this->type !== null )
		{
			return true;
		}

		// id always exists
		if ( $key === 'id' and $this->id !== null )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this identifier
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// type
		if ( $this->has('type') )
		{
			$keys[] = 'type';
		}

		// id
		if ( $this->has('id') )
		{
			$keys[] = 'id';
		}

		// meta
		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this identifier
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	protected function getValue($key)
	{
		if ( ! $this->has($key) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this identifier.');
		}

		if ( $key === 'meta' )
		{
			return $this->getMeta();
		}

		return $this->$key;
	}

	/**
	 * Is this Resource a null resource?
	 *
	 * @return boolean false
	 */
	public function isNull()
	{
		return false;
	}

	/**
	 * Is this Resource an identifier?
	 *
	 * @return boolean true
	 */
	public function isIdentifier()
	{
		return true;
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
	 * @return boolean false
	 */
	public function isCollection()
	{
		return false;
	}
}
