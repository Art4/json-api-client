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
			$this->id = (string) $object->id;
		}

		if ( property_exists($object, 'links') )
		{
			$this->setLinks(new ErrorLink($object->links));
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
			$this->source = new ErrorSource($object->source);
		}

		if ( property_exists($object, 'meta') )
		{
			$this->setMeta($object->meta);
		}

		return $this;
	}
}
