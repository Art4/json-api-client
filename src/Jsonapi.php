<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * JSON API Object
 *
 * @see http://jsonapi.org/format/#document-jsonapi-object
 */
class Jsonapi implements AccessInterface
{
	use AccessTrait;

	use MetaTrait;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	protected $version = null;

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
			throw new ValidationException('Jsonapi has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		if ( property_exists($object, 'version') )
		{
			if ( is_object($object->version) or is_array($object->version) )
			{
				throw new ValidationException('property "version" cannot be an object or array, "' . gettype($object->version) . '" given.');
			}

			$this->version = strval($object->version);
		}

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}

	/**
	 * Check if a value exists in this jsonapi object
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// version
		if ( $key === 'version' and $this->version !== null )
		{
			return true;
		}

		// meta
		if ( $key === 'meta' and $this->hasMeta() )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this object
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// version
		if ( $this->has('version') )
		{
			$keys[] = 'version';
		}

		// meta
		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		return $keys;
	}

	/**
	 * Get a value by the key
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	protected function getValue($key)
	{
		if ( ! $this->has($key) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this jsonapi object.');
		}

		// meta
		if ( $key === 'meta' )
		{
			return $this->getMeta();
		}

		return $this->$key;
	}
}
