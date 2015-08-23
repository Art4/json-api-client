<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Utils\LinksTrait;
use Art4\JsonApiClient\Resource\Identifier;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Relationship Object
 *
 * @see http://jsonapi.org/format/#document-resource-object-relationships
 */
class Relationship
{
	use MetaTrait;

	use LinksTrait;

	/**
	 * @var null|ResourceIdentifier
	 */
	protected $data = false; // Cannot be null, because null is a valid value too

	/**
	 * @param object $object The relationship object
	 *
	 * @return Relationship
	 *
	 * @throws ValidationException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Relationship has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'links') and ! property_exists($object, 'data') and ! property_exists($object, 'meta') )
		{
			throw new ValidationException('A Relationship object MUST contain at least one of the following properties: links, data, meta');
		}

		if ( property_exists($object, 'links') )
		{
			$this->setLinks(new RelationshipLink($object->links));
		}

		if ( property_exists($object, 'data') )
		{
			$this->setData($object->data);
		}

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}

	/**
	 * Check if a value exists in this relationship
		*
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// links
		if ( $key === 'links' and $this->hasLinks() )
		{
			return true;
		}

		// data
		if ( $key === 'data' and $this->data !== false )
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
	 * Returns the keys of all setted values in this relationship
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// links
		if ( $this->has('links') )
		{
			$keys[] = 'links';
		}

		// data
		if ( $this->has('data') )
		{
			$keys[] = 'data';
		}

		// meta
		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this relationship
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in Relationship.');
		}

		if ( $key === 'meta' )
		{
			return $this->getMeta();
		}

		if ( $key === 'links' )
		{
			return $this->getLinks();
		}

		return $this->$key;
	}

	/**
	 * Set the data for this relationship
	 *
	 * @throws ValidationException If $data isn't null or ResourceIdentifier
	 *
	 * @param null|object $data The Data
	 * @return self
	 */
	protected function setData($data)
	{
		$this->data = $this->parseData($data);
	}

	/**
	 * Parse the data value
	 *
	 * @throws ValidationException If $data isn't null or an object
	 *
	 * @param null|object $data Data value
	 * @return null|ResourceIdentifier The parsed data
	 */
	protected function parseData($data)
	{
		if ( $data === null )
		{
			return $data;
		}

		if ( is_array($data) )
		{
			$resource_array = array();

			if ( count($data) > 0 )
			{
				foreach ($data as $data_obj)
				{
					$resource_obj = $this->parseData($data_obj);

					if ( ! ($resource_obj instanceof Identifier) )
					{
						throw new ValidationException('Data has to be instance of "Resource\\Identifier", "' . gettype($data) . '" given.');
					}

					$resource_array[] = $resource_obj;
				}
			}

			return $resource_array;
		}

		if ( ! is_object($data) )
		{
			throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
		}

		return new Identifier($data);
	}
}
