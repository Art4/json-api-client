<?php

namespace Art4\JsonApiClient;

/**
 * Access Interface
 */
interface AccessInterface
{
	public function get($key);

	public function has($key);

	public function getKeys();

	//public function asArray();
}
