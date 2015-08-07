<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Utils\LinksTrait;

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
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'links') and ! property_exists($object, 'data') and ! property_exists($object, 'meta') )
		{
			throw new \InvalidArgumentException('$object MUST contain at least one of the following properties: links, data, meta');
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
	 * Check if data exists in this relationship
	 *
	 * @return bool true if data exists, false if not
	 */
	public function hasData()
	{
		return $this->data !== false;
	}

	/**
	 * Get the data of this relationship
	 *
	 * @throws \RuntimeException If data wasn't set, you can't get it
	 *
	 * @return null|ResourceIdentifier|Resource The data
	 */
	public function getData()
	{
		if ( ! $this->hasData() )
		{
			throw new \RuntimeException('You can\'t get "data", because it wasn\'t set.');
		}

		return $this->data;
	}

	/**
	 * Set the data for this relationship
	 *
	 * @throws \InvalidArgumentException If $data isn't null or ResourceIdentifier
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
	 * @throws \InvalidArgumentException If $data isn't null or an object
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

					if ( ! ($resource_obj instanceof ResourceIdentifier) )
					{
						throw new \InvalidArgumentException('Data has to be instance of "ResourceIdentifier", "' . gettype($data) . '" given.');
					}

					$resource_array[] = $resource_obj;
				}
			}

			return $resource_array;
		}

		if ( ! is_object($data) )
		{
			throw new \InvalidArgumentException('Data value has to be null or an object, "' . gettype($data) . '" given.');
		}
		
		return new ResourceIdentifier($data);
	}
}
