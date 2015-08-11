<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Utils\LinksTrait;

/**
 * Document Top Level Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 */
class Document
{
	use MetaTrait;

	use LinksTrait;

	/**
	 * @var null|ResourceIdentifier
	 */
	protected $data = false; // Cannot be null, because null is a valid value too

	protected $errors = array();

	protected $jsonapi = null;

	protected $included = null;

	/**
	 * @param object $object The document body
	 *
	 * @return Document
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'data') and ! property_exists($object, 'meta') and ! property_exists($object, 'errors') )
		{
			throw new \InvalidArgumentException('$object MUST contain at least one of the following properties: data, errors, meta');
		}

		if ( property_exists($object, 'data') and property_exists($object, 'errors') )
		{
			throw new \InvalidArgumentException('The properties `data` and `errors` MUST NOT coexist in $object.');
		}

		if ( property_exists($object, 'data') )
		{
			$this->setData($object->data);
		}

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		if ( property_exists($object, 'errors') )
		{
			if ( ! is_array($object->errors) )
			{
				throw new \InvalidArgumentException('Errors have to be in an array, "' . gettype($object->errors) . '" given.');
			}

			if ( count($object->errors) === 0 )
			{
				throw new \InvalidArgumentException('Errors array cannot be empty and MUST have at least one object');
			}

			foreach ($object->errors as $error_obj)
			{
				$this->addError(new Error($error_obj));
			}
		}

		if ( property_exists($object, 'included') )
		{
			if ( ! property_exists($object, 'data') )
			{
				throw new \InvalidArgumentException('If $object does not contain a `data` property, the `included` property MUST NOT be present either.');
			}

			if ( ! is_array($object->included) )
			{
				throw new \InvalidArgumentException('included member has to be an array, "' . gettype($object->included) . '" given.');
			}

			foreach ($object->included as $resource_obj)
			{
				$this->addInclude(new Resource($resource_obj));
			}
		}

		if ( property_exists($object, 'jsonapi') )
		{
			$this->jsonapi = new Jsonapi($object->jsonapi);
		}

		if ( property_exists($object, 'links') )
		{
			$this->setLinks(new DocumentLink($object->links));
		}

		return $this;
	}

	/**
	 * Check if a value exists in this document
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// data
		if ( $key === 'data' and $this->data !== false )
		{
			return true;
		}

		// errors
		if ( $key === 'errors' and count($this->errors) > 0 )
		{
			return true;
		}

		// meta
		if ( $key === 'meta' and $this->hasMeta() )
		{
			return true;
		}

		// jsonapi
		if ( $key === 'jsonapi' and $this->jsonapi !== null )
		{
			return true;
		}

		// links
		if ( $key === 'links' and $this->hasLinks() )
		{
			return true;
		}

		// included
		if ( $key === 'included' and $this->included !== null )
		{
			return true;
		}

		return false;
	}

	/**
	 * Returns the keys of all setted values in this document
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// data
		if ( $this->has('data') )
		{
			$keys[] = 'data';
		}

		// errors
		if ( $this->has('errors') )
		{
			$keys[] = 'errors';
		}

		// meta
		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		// jsonapi
		if ( $this->has('jsonapi') )
		{
			$keys[] = 'jsonapi';
		}

		// links
		if ( $this->has('links') )
		{
			$keys[] = 'links';
		}

		// included
		if ( $this->has('included') )
		{
			$keys[] = 'included';
		}

		return $keys;
	}

	/**
	 * Get a value by the key of this document
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in Document.');
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
	 * Convert this document in an array
	 *
	 * @return array
	 */
	public function asArray()
	{
		$return = array();

		foreach($this->getKeys() as $key)
		{
			$return[$key] = $this->get($key);
		}

		return $return;
	}

	/**
	 * Check if data exists in this document
	 *
	 * @return bool true if data exists, false if not
	 */
	public function hasData()
	{
		return $this->has('data');
	}

	/**
	 * Get the data of this document
	 *
	 * @throws \RuntimeException If data wasn't set, you can't get it
	 *
	 * @return null|ResourceIdentifier|Resource The data
	 */
	public function getData()
	{
		return $this->get('data');
	}

	/**
	 * Check if errors exists in this document
	 *
	 * @return bool true if errors exists, false if not
	 */
	public function hasErrors()
	{
		return $this->has('errors');
	}

	/**
	 * Get the errors of this document
	 *
	 * @return Error[] The errors or an empty array
	 */
	public function getErrors()
	{
		return $this->get('errors');
	}

	/**
	 * Check if jsonapi exists in this document
	 *
	 * @return bool true if jsonapi exists, false if not
	 */
	public function hasJsonapi()
	{
		return $this->has('jsonapi');
	}

	/**
	 * Get the jsonapi object of this document
	 *
	 * @throws \RuntimeException If jsonapi wasn't set, you can't get it
	 *
	 * @return Jsonapi The jsonapi object
	 */
	public function getJsonapi()
	{
		return $this->get('jsonapi');
	}

	/**
	 * Check if included exists in this document
	 *
	 * @return bool true if included exists, false if not
	 */
	public function hasIncluded()
	{
		return $this->has('included');
	}

	/**
	 * Get the included objects array of this document
	 *
	 * @throws \RuntimeException If included wasn't set, you can't get it
	 *
	 * @return Resource[] The included objects as array
	 */
	public function getIncluded()
	{
		return $this->get('included');
	}

	/**
	 * Set the data for this document
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

		$object_vars = get_object_vars($data);

		// the properties must be type and id
		if ( count($object_vars) === 2 )
		{
			$resource = new ResourceIdentifier($data);
		}
		// the 3 properties must be type, id and meta
		elseif ( count($object_vars) === 3 and property_exists($data, 'meta') )
		{
			$resource = new ResourceIdentifier($data);
		}
		else
		{
			$resource = new Resource($data);
		}

		return $resource;
	}

	/**
	 * Add an error to this document
	 *
	 * @param Error $error The Error
	 * @return self
	 */
	protected function addError(Error $error)
	{
		$this->errors[] = $error;

		return $this;
	}

	/**
	 * Add an Resource object to the included property
	 *
	 * @param Resource $resource The Resource
	 * @return self
	 */
	protected function addInclude(Resource $resource)
	{
		$this->included[] = $resource;

		return $this;
	}
}
