<?php

namespace Art4\JsonApiClient\Tests\Fixtures;

use Art4\JsonApiClient\Utils\FactoryInterface;

final class Factory implements FactoryInterface
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
		return $this->testcase
			->getMockBuilder('Art4\JsonApiClient\\' . $name . 'Interface') // Mock only the interfaces
			->getMock();
	}
}
