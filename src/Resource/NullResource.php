<?php

namespace Art4\JsonApiClient\Resource;

/**
 * Null Resource
 */
class NullResource implements ResourceInterface
{
	/**
	 * Is this Resource a null resource?
	 *
	 * @return boolean true
	 */
	public function isNull()
	{
		return true;
	}

	/**
	 * Is this Resource an identifier?
	 *
	 * @return boolean false
	 */
	public function isIdentifier()
	{
		return false;
	}

	/**
	 * Is this Resource an item?
	 *
	 * @return boolean false
	 */
	public function isItem()
	{
		return false;
	}

	/**
	 * Is this Resource a collection?
	 *
	 * @return boolean true
	 */
	public function isCollection()
	{
		return false;
	}
}
