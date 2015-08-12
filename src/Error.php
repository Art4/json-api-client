<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Utils\LinksTrait;

/**
 * Error Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
class Error
{
	use MetaTrait;

	use LinksTrait;

	protected $id = null;

	protected $status = null;

	protected $code = null;

	protected $title = null;

	protected $detail = null;

	protected $source = null;

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
		}

		if ( property_exists($object, 'id') )
		{
			if ( ! is_string($object->id) )
			{
				throw new \InvalidArgumentException('property "id" has to be a string, "' . gettype($object->id) . '" given.');
			}

			$this->id = (string) $object->id;
		}

		if ( property_exists($object, 'links') )
		{
			$this->setLinks(new ErrorLink($object->links));
		}

		if ( property_exists($object, 'status') )
		{
			if ( ! is_string($object->status) )
			{
				throw new \InvalidArgumentException('property "status" has to be a string, "' . gettype($object->status) . '" given.');
			}

			$this->status = (string) $object->status;
		}

		if ( property_exists($object, 'code') )
		{
			if ( ! is_string($object->code) )
			{
				throw new \InvalidArgumentException('property "code" has to be a string, "' . gettype($object->code) . '" given.');
			}

			$this->code = (string) $object->code;
		}

		if ( property_exists($object, 'title') )
		{
			if ( ! is_string($object->title) )
			{
				throw new \InvalidArgumentException('property "title" has to be a string, "' . gettype($object->title) . '" given.');
			}

			$this->title = (string) $object->title;
		}

		if ( property_exists($object, 'detail') )
		{
			if ( ! is_string($object->detail) )
			{
				throw new \InvalidArgumentException('property "detail" has to be a string, "' . gettype($object->detail) . '" given.');
			}

			$this->detail = (string) $object->detail;
		}

		if ( property_exists($object, 'source') )
		{
			$this->source = new ErrorSource($object->source);
		}

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}

	/**
	 * Check if a value exists in this object
	 *
	 * @param string $key The key of the value
	 * @return bool true if data exists, false if not
	 */
	public function has($key)
	{
		// id
		if ( $key === 'id' and $this->id !== null )
		{
			return true;
		}

		// links
		if ( $key === 'links' and $this->hasLinks() )
		{
			return true;
		}

		// status
		if ( $key === 'status' and $this->status !== null )
		{
			return true;
		}

		// code
		if ( $key === 'code' and $this->code !== null )
		{
			return true;
		}

		// title
		if ( $key === 'title' and $this->title !== null )
		{
			return true;
		}

		// detail
		if ( $key === 'detail' and $this->detail !== null )
		{
			return true;
		}

		// source
		if ( $key === 'source' and $this->source !== null )
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
	 * Returns the keys of all setted values
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array();

		// id
		if ( $this->has('id') )
		{
			$keys[] = 'id';
		}

		// links
		if ( $this->has('links') )
		{
			$keys[] = 'links';
		}

		// status
		if ( $this->has('status') )
		{
			$keys[] = 'status';
		}

		// code
		if ( $this->has('code') )
		{
			$keys[] = 'code';
		}

		// title
		if ( $this->has('title') )
		{
			$keys[] = 'title';
		}

		// detail
		if ( $this->has('detail') )
		{
			$keys[] = 'detail';
		}

		// source
		if ( $this->has('source') )
		{
			$keys[] = 'source';
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
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in this error object.');
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
	 * Check if id exists
	 *
	 * @return bool true if id exists, false if not
	 */
	public function hasId()
	{
		return $this->has('id');
	}

	/**
	 * Get the id
	 *
	 * @throws \RuntimeException If id wasn't set, you can't get it
	 *
	 * @return string The id
	 */
	public function getId()
	{
		return $this->get('id');
	}

	/**
	 * Check if status exists
	 *
	 * @return bool true if status exists, false if not
	 */
	public function hasStatus()
	{
		return $this->has('status');
	}

	/**
	 * Get the status
	 *
	 * @throws \RuntimeException If status wasn't set, you can't get it
	 *
	 * @return string The status
	 */
	public function getStatus()
	{
		return $this->get('status');
	}

	/**
	 * Check if code exists
	 *
	 * @return bool true if code exists, false if not
	 */
	public function hasCode()
	{
		return $this->has('code');
	}

	/**
	 * Get the code
	 *
	 * @throws \RuntimeException If code wasn't set, you can't get it
	 *
	 * @return string The code
	 */
	public function getCode()
	{
		return $this->get('code');
	}

	/**
	 * Check if title exists
	 *
	 * @return bool true if title exists, false if not
	 */
	public function hasTitle()
	{
		return $this->has('title');
	}

	/**
	 * Get the title
	 *
	 * @throws \RuntimeException If title wasn't set, you can't get it
	 *
	 * @return string The title
	 */
	public function getTitle()
	{
		return $this->get('title');
	}

	/**
	 * Check if detail exists
	 *
	 * @return bool true if detail exists, false if not
	 */
	public function hasDetail()
	{
		return $this->has('detail');
	}

	/**
	 * Get the detail
	 *
	 * @throws \RuntimeException If detail wasn't set, you can't get it
	 *
	 * @return string The detail
	 */
	public function getDetail()
	{
		return $this->get('detail');
	}

	/**
	 * Check if source exists
	 *
	 * @return bool true if source exists, false if not
	 */
	public function hasSource()
	{
		return $this->has('source');
	}

	/**
	 * Get the source
	 *
	 * @throws \RuntimeException If source wasn't set, you can't get it
	 *
	 * @return ErrorSource The source
	 */
	public function getSource()
	{
		return $this->get('source');
	}
}
