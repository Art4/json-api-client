<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\Meta;

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
	 * Set the meta for this document
	 *
	 * @throws \InvalidArgumentException If $meta isn't an object
	 *
	 * @param null|ResourceIdentifier $data The Data
	 * @return self
	 */
	protected function setMeta($meta)
	{
		$this->meta = new Meta($meta);
	}
}
