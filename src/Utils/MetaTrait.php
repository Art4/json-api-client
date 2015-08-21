<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\Meta;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Trait for Meta properties
 */
trait MetaTrait
{
	protected $meta = null;

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
	 * @throws AccessException If meta wasn't set, you can't get it
	 *
	 * @return Meta The meta object
	 */
	public function getMeta()
	{
		if ( ! $this->hasMeta() )
		{
			throw new AccessException('You can\'t get "meta", because it wasn\'t set.');
		}

		return $this->meta;
	}

	/**
	 * Set the meta for this document
	 *
	 * @throws ValidationException If $meta isn't an object
	 *
	 * @param null|ResourceIdentifier $data The Data
	 * @return self
	 */
	protected function setMeta($meta)
	{
		$this->meta = new Meta($meta);
	}
}
