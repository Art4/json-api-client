<?php

namespace Art4\JsonApiClient\Utils;

/**
 * Trait for array conversion
 */
trait AccessTrait
{
	/**
	 * Convert this object in an array
	 *
	 * @return array
	 */
	public function asArray()
	{
		$return = array();

		foreach($this->getKeys() as $key)
		{
			$return[$key] = $this->get($key);
		}

		return $return;
	}
}
