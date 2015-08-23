<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Utils\LinksTrait;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Resource\Identifier;
use Art4\JsonApiClient\Resource\Item;
use Art4\JsonApiClient\Resource\Collection;
use Art4\JsonApiClient\Resource\NullResource;

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
	 * @throws ValidationException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( ! property_exists($object, 'data') and ! property_exists($object, 'meta') and ! property_exists($object, 'errors') )
		{
			throw new ValidationException('$object MUST contain at least one of the following properties: data, errors, meta');
		}

		if ( property_exists($object, 'data') and property_exists($object, 'errors') )
		{
			throw new ValidationException('The properties `data` and `errors` MUST NOT coexist in $object.');
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
				throw new ValidationException('Errors have to be in an array, "' . gettype($object->errors) . '" given.');
			}

			if ( count($object->errors) === 0 )
			{
				throw new ValidationException('Errors array cannot be empty and MUST have at least one object');
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
				throw new ValidationException('If $object does not contain a `data` property, the `included` property MUST NOT be present either.');
			}

			$this->included = new Collection($object->included);
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
			throw new AccessException('"' . $key . '" doesn\'t exist in Document.');
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
	 * Set the data for this document
	 *
	 * @throws ValidationException If $data isn't ResourceInterface
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
	 * @return ResourceInterface The parsed data
	 */
	protected function parseData($data)
	{
		if ( $data === null )
		{
			return new NullResource();
		}

		if ( is_array($data) )
		{
			return new Collection($data);
		}

		if ( ! is_object($data) )
		{
			throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
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
}
