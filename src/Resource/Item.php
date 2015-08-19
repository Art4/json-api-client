<?php

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\Attributes;
use Art4\JsonApiClient\RelationshipCollection;
use Art4\JsonApiClient\Link;
use Art4\JsonApiClient\Utils\LinksTrait;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Resource Object
 *
 * @see http://jsonapi.org/format/#document-resource-objects
 */
class Item extends Identifier
{
	use LinksTrait;

	protected $attributes = null;

	protected $relationships = null;

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object)
	{
		// check type, id and meta in ResourceIdentifier
		parent::__construct($object);

		if ( property_exists($object, 'attributes') )
		{
			$this->attributes = new Attributes($object->attributes);
		}

		if ( property_exists($object, 'relationships') )
		{
			$this->relationships = new RelationshipCollection($object->relationships, $this);
		}

		if ( property_exists($object, 'links') )
		{
			$this->setLinks(new Link($object->links));
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
		// meta, type, id
		if ( parent::has($key) === true )
		{
			return true;
		}

		// attributes
		if ( $key === 'attributes' and $this->attributes !== null )
		{
			return true;
		}

		// relationships
		if ( $key === 'relationships' and $this->relationships !== null )
		{
			return true;
		}

		// links
		if ( $key === 'links' and $this->hasLinks() )
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
		$keys = parent::getKeys();

		// attributes
		if ( $this->has('attributes') )
		{
			$keys[] = 'attributes';
		}

		// relationships
		if ( $this->has('relationships') )
		{
			$keys[] = 'relationships';
		}

		// links
		if ( $this->has('links') )
		{
			$keys[] = 'links';
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
	 * @return boolean true
	 */
	public function isItem()
	{
		return true;
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
