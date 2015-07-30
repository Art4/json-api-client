<?php

namespace Art4\JsonApiClient;

/**
 * Document Top Level Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 */
class Document
{
	/**
	 * @var null|ResourceIdentifier
	 */
	protected $data = false; // Cannot be null, because null is a valid value too

	protected $meta = null;

	protected $errors = array();

	protected $jsonapi = null;

	protected $links = null;

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

			$this->included = $object->included;
		}

		if ( property_exists($object, 'jsonapi') )
		{
			$this->jsonapi = $object->jsonapi;
		}

		if ( property_exists($object, 'links') )
		{
			$this->links = $object->links;
		}

		return $this;
	}

	/**
	 * Check if data exists in this document
	 *
	 * @return bool true if data exists, false if not
	 */
	public function hasData()
	{
		return $this->data !== false;
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
		if ( ! $this->hasData() )
		{
			throw new \RuntimeException('You can\'t get "data", because it wasn\'t set.');
		}

		return $this->data;
	}

	/**
	 * Check if errors exists in this document
	 *
	 * @return bool true if errors exists, false if not
	 */
	public function hasErrors()
	{
		return count($this->getErrors()) > 0;
	}

	/**
	 * Get the errors of this document
	 *
	 * @return Error[] The errors or an empty array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Check if meta exists in this document
	 *
	 * @return bool true if meta exists, false if not
	 */
	public function hasMeta()
	{
		return $this->meta !== null;
	}

	/**
	 * Get the meta of this document
	 *
	 * @throws \RuntimeException If meta wasn't set, you can't get it
	 *
	 * @return Meta The meta object
	 */
	public function getMeta()
	{
		if ( ! $this->hasMeta() )
		{
			throw new \RuntimeException('You can\'t get "meta", because it wasn\'t set.');
		}

		return $this->meta;
	}

	/**
	 * Set the data for this document
	 *
	 * @throws \InvalidArgumentException If $data isn't null or ResourceIdentifier
	 *
	 * @param null|ResourceIdentifier $data The Data
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
	 * Set the meta for this document
	 *
	 * @throws \InvalidArgumentException If $meta isn't an object
	 *
	 * @param null|ResourceIdentifier $data The Data
	 * @return self
	 */
	protected function setMeta($meta)
	{
		if ( ! is_object($meta) )
		{
			throw new \InvalidArgumentException('Meta value has to be an object, "' . gettype($meta) . '" given.');
		}

		$this->meta = new Meta($meta);
	}
}
