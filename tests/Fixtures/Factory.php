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
		if ( isset($this->classes[$name]) )
		{
			return $this->testcase
				->getMockBuilder($this->classes[$name] . 'Interface') // Mock only the interfaces
				->disableOriginalConstructor()
				->getMock();
		}
	}
}
