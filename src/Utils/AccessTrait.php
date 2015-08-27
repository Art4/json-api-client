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
			$val = $this->get($key);

			if (!is_object($val))
			{
				$return[$key] = $val;
			}
			elseif (is_callable([$val, 'asArray']))
			{
				$return[$key] = $val->asArray();
			}
			else
			{
				// Fallback for stdClass objects
				$return[$key] = json_decode(json_encode($val), true);
			}

		}

		return $return;
	}

	/**
	 * Convert object in a json string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return json_encode($this->asArray());
	}
}
