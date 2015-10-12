<?php

namespace Art4\JsonApiClient\Utils;

use \SplStack;

final class AccessKey extends SplStack
{
	/**
	 * @var string Ras key
	 */
	public $raw = '';

	/**
	 * Transforms the Key to a string
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->raw;
	}
}
