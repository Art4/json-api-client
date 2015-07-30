<?php

namespace Art4\JsonApiClient;

/**
 * Error Object
 *
 * @see http://jsonapi.org/format/#error-objects
 */
class Error
{
	protected $id = null;

	protected $links = null;

	protected $status = null;

	protected $code = null;

	protected $title = null;

	protected $detail = null;

	protected $source = null;

	protected $meta = null;

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
			$this->id = (string) $object->id;
		}

		if ( property_exists($object, 'links') )
		{
			$this->links = $object->links;
		}

		if ( property_exists($object, 'status') )
		{
			$this->status = (string) $object->status;
		}

		if ( property_exists($object, 'code') )
		{
			$this->code = (string) $object->code;
		}

		if ( property_exists($object, 'title') )
		{
			$this->title = (string) $object->title;
		}

		if ( property_exists($object, 'detail') )
		{
			$this->detail = (string) $object->detail;
		}

		if ( property_exists($object, 'source') )
		{
			$this->source = $object->source;
		}

		if ( property_exists($object, 'meta') )
		{
			$this->meta = $object->meta;
		}

		return $this;
	}
}
