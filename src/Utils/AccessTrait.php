<?php

namespace Art4\JsonApiClient\Utils;

/**
 * Trait for array conversion
 */
trait AccessTrait
{
	/**
	 * Transforms objects to arrays
	 *
	 * @param $val
	 * @return mixed
	 */
	protected function objectTransform($val)
	{
		if (!is_object($val))
		{
			return $val;
		}
		elseif (is_callable([$val, 'asArray']))
		{
			return $val->asArray(true);
		}
		else
		{
			// Fallback for stdClass objects
			return json_decode(json_encode($val), true);
		}
	}

	/**
	 * Convert this object in an array
	 *
	 * @param bool $fullArray If true, objects are transformed into arrays recursively
	 * @return array
	 */
	public function asArray($fullArray = false)
	{
		$return = array();

		foreach($this->getKeys() as $key)
		{
			$val = $this->get($key);

			if ($fullArray) {
				$return[$key] = $this->objectTransform($val);
			} else {
				$return[$key] = $val;
			}
		}

		return $return;
	}
}
