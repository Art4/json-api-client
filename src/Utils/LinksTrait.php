<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\Link;

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
	 * @return null|Link The link object or null
	 */
	public function getLinks()
	{
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
