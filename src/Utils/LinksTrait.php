<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\AccessInterface;

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
	 * @return null|AccessInterface The link object or null
	 */
	public function getLinks()
	{
		return $this->links;
	}

	/**
	 * Set the links for this document
	 *
	 * @param AccessInterface $link The Link
	 * @return self
	 */
	protected function setLinks(AccessInterface $link)
	{
		$this->links = $link;
	}
}
