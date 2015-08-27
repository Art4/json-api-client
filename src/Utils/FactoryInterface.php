<?php

namespace Art4\JsonApiClient\Utils;

/**
 * Factory Interface
 */
interface FactoryInterface
{
	/**
	 * Create a new instance of a class
	 *
	 * @param  string $name
	 * @param  array  $args
	 * @return object
	 */
	public function make($name, array $args = []);
}
