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
	 * Check if id exists
	 *
	 * @return bool true if id exists, false if not
	 */
	public function hasId()
	{
		return $this->id !== null;
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
		if ( ! $this->hasId() )
		{
			throw new \RuntimeException('You can\'t get "id", because it wasn\'t set.');
		}

		return $this->id;
	}

	/**
	 * Check if status exists
	 *
	 * @return bool true if status exists, false if not
	 */
	public function hasStatus()
	{
		return $this->status !== null;
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
		if ( ! $this->hasStatus() )
		{
			throw new \RuntimeException('You can\'t get "status", because it wasn\'t set.');
		}

		return $this->status;
	}

	/**
	 * Check if code exists
	 *
	 * @return bool true if code exists, false if not
	 */
	public function hasCode()
	{
		return $this->code !== null;
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
		if ( ! $this->hasCode() )
		{
			throw new \RuntimeException('You can\'t get "code", because it wasn\'t set.');
		}

		return $this->code;
	}

	/**
	 * Check if title exists
	 *
	 * @return bool true if title exists, false if not
	 */
	public function hasTitle()
	{
		return $this->title !== null;
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
		if ( ! $this->hasTitle() )
		{
			throw new \RuntimeException('You can\'t get "title", because it wasn\'t set.');
		}

		return $this->title;
	}

	/**
	 * Check if detail exists
	 *
	 * @return bool true if detail exists, false if not
	 */
	public function hasDetail()
	{
		return $this->detail !== null;
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
		if ( ! $this->hasDetail() )
		{
			throw new \RuntimeException('You can\'t get "detail", because it wasn\'t set.');
		}

		return $this->detail;
	}

	/**
	 * Check if source exists
	 *
	 * @return bool true if source exists, false if not
	 */
	public function hasSource()
	{
		return $this->source !== null;
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
		if ( ! $this->hasSource() )
		{
			throw new \RuntimeException('You can\'t get "error source", because it wasn\'t set.');
		}

		return $this->source;
	}
}
