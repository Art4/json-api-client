<?php

namespace Art4\JsonApiClient\Tests\Fixtures;

use Art4\JsonApiClient\Utils\Factory as OrigFactory;

class Factory extends OrigFactory
{
	public $testcase;
	
	/**
	 * Create a new instance of a class
	 *
	 * @param  string $name
	 * @param  array  $args
	 * @return object
	 */
	public function make($name, array $args = [])
	{
		// Handle possible Exceptions
		parent::make($name, $args);

		return $this->testcase->getMockBuilder($this->classes[$name])->disableOriginalConstructor()->getMock();
	}
}
