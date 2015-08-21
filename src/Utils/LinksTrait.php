<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\Link;
use Art4\JsonApiClient\Exception\AccessException;

/**
 * Trait for Links properties
 */
trait LinksTrait
{
	protected $links = null;

	/**
	 * Check if links exists in this document
	 *
	 * @return bool true if meta exists, false if not
	 */
	public function hasLinks()
	{
		return $this->links !== null;
	}

	/**
	 * Get the links of this document
	 *
	 * @throws AccessException If links wasn't set, you can't get it
	 *
	 * @return Link The link object
	 */
	public function getLinks()
	{
		if ( ! $this->hasLinks() )
		{
			throw new AccessException('You can\'t get "links", because it wasn\'t set.');
		}

		return $this->links;
	}

	/**
	 * Set the links for this document
	 *
	 * @param Link $link The Link
	 * @return self
	 */
	protected function setLinks(Link $link)
	{
		$this->links = $link;
	}
}
