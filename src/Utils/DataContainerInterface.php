<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\AccessInterface;

/**
 * DataContainer Interface
 */
interface DataContainerInterface extends AccessInterface
{
	/**
	 * Set a value
	 *
	 * @param string $key The Key
	 * @param mixed $value The Value
	 *
	 * @return self
	 */
	public function set($key, $value);
}
