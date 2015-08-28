<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Utils\LinksTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Document Top Level Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 */
class Document implements AccessInterface
{
	use AccessTrait;

	use MetaTrait;

	use LinksTrait;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * @var null|ResourceIdentifier
	 */
	protected $data = false; // Cannot be null, because null is a valid value too

	protected $errors = null;

	protected $jsonapi = null;

	protected $included = null;

	/**
	 * @param object $object The document body
	 *
	 * @return Document
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
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

		$this->manager = $manager;

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
			$this->errors = $this->manager->getFactory()->make(
				'ErrorCollection',
				[$object->errors, $this->manager]
			);
		}

		if ( property_exists($object, 'included') )
		{
			if ( ! property_exists($object, 'data') )
			{
				throw new ValidationException('If $object does not contain a `data` property, the `included` property MUST NOT be present either.');
			}

			$this->included = $this->manager->getFactory()->make(
				'Resource\Collection',
				[$object->included, $this->manager]
			);
		}

		if ( property_exists($object, 'jsonapi') )
		{
			$this->jsonapi = $this->manager->getFactory()->make(
				'Jsonapi',
				[$object->jsonapi, $this->manager]
			);
		}

		if ( property_exists($object, 'links') )
		{
			$this->setLinks($this->manager->getFactory()->make(
				'DocumentLink',
				[$object->links, $this->manager]
			));
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
			return $this->manager->getFactory()->make(
				'Resource\NullResource',
				[$data, $this->manager]
			);
		}

		if ( is_array($data) )
		{
			return $this->manager->getFactory()->make(
				'Resource\Collection',
				[$data, $this->manager]
			);
		}

		if ( ! is_object($data) )
		{
			throw new ValidationException('Data value has to be null or an object, "' . gettype($data) . '" given.');
		}

		$object_vars = get_object_vars($data);

		// the properties must be type and id
		if ( count($object_vars) === 2 )
		{
			$resource = $this->manager->getFactory()->make(
				'Resource\Identifier',
				[$data, $this->manager]
			);
		}
		// the 3 properties must be type, id and meta
		elseif ( count($object_vars) === 3 and property_exists($data, 'meta') )
		{
			$resource = $this->manager->getFactory()->make(
				'Resource\Identifier',
				[$data, $this->manager]
			);
		}
		else
		{
			$resource = $this->manager->getFactory()->make(
				'Resource\Item',
				[$data, $this->manager]
			);
		}

		return $resource;
	}
}
