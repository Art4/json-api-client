<?php

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 */
class Collection implements ResourceInterface
{
	protected $resources = array();

	/**
	 * @param array $resources The resources as array
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($resources)
	{
		if ( ! is_array($resources) )
		{
			throw new ValidationException('Resources for a collection has to be in an array, "' . gettype($resources) . '" given.');
		}

		if ( count($resources) > 0 )
		{
			foreach ($resources as $resource)
			{
				$this->addResource($this->parseResource($resource));
			}
		}

		return $this;
	}

	/**
	 * Check if a value exists in this resource
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		if ( is_object($key) or is_array($key) )
		{
			return false;
		}

		if ( is_string($key) and ! ctype_digit($key) )
		{
			return false;
		}

		$key = intval($key);

		if ( isset($this->resources[$key]) )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this resource
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		if ( count($this->resources) === 0 )
		{
			return $keys;
		}

		foreach ( $this->resources as $key => $value )
		{
			$keys[] = $key;
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this resource
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in this resource.');
		}

		return $this->resources[$key];
	}

	/**
	 * Get all resources from this collection
	 *
	 * @return ResourceInterface[] The resources as array
	 */
	public function asArray()
	{
		return $this->resources;
	}

	/**
	 * Generate a new resource
	 *
	 * @param ResourceInterface $resource The resource
	 * @return ResourceInterface[] The resources as array
	 */
	protected function addResource(ResourceInterface $resource)
	{
		return $this->resources[] = $resource;
	}

	/**
	 * Generate a new resource from an object
	 *
	 * @param object $data The resource data
	 * @return ResourceInterface The resource
	 */
	protected function parseResource($data)
	{
		if ( ! is_object($data) )
		{
			throw new ValidationException('Resources inside a collection MUST be objects, "' . gettype($data) . '" given.');
		}

		$object_vars = get_object_vars($data);

		// the properties must be type and id
		if ( count($object_vars) === 2 )
		{
			$resource = new Identifier($data);
		}
		// the 3 properties must be type, id and meta
		elseif ( count($object_vars) === 3 and property_exists($data, 'meta') )
		{
			$resource = new Identifier($data);
		}
		else
		{
			$resource = new Item($data);
		}

		return $resource;
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
		return true;
	}
}
