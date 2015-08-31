<?php

namespace Art4\JsonApiClient\Tests\Fixtures;

/**
 * Helper Trait
 */
trait HelperTrait
{
	/**
	 * Json Values Provider
	 *
	 * @see http://json.org/
	 */
	public function jsonValuesProvider()
	{
		return array(
			array(new \stdClass()),
			array(array()),
			array('string'),
			array(456),
			array(159.654),
			array(-15E-3),
			array(true),
			array(false),
			array(null),
		);
	}

	/**
	 * Builds a Manager Mock
	 */
	public function buildManagerMock()
	{
		// Mock factory
		$factory = new Factory;
		$factory->testcase = $this;

		// Mock Manager
		$manager = $this->getMockBuilder('Art4\JsonApiClient\Utils\Manager')
			->getMock();

		$manager->expects($this->any())
			->method('getFactory')
			->will($this->returnValue($factory));

		return $manager;
	}
}
