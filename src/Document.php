<?php

namespace Youthweb\JsonApiClient;

/**
 * PHP JSON API client
 *
 * Website: http://github.com/youthweb/json-api-client
 */
class Document
{
	protected $data = null;

	protected $meta = null;

	protected $errors = null;

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
	public function parse($object)
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
			$this->data = $object->data;
		}

		if ( property_exists($object, 'meta') )
		{
			$this->meta = $object->meta;
		}

		if ( property_exists($object, 'errors') )
		{
			$this->errors = $object->errors;
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
}
