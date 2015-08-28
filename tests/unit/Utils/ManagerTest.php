<?php

namespace Art4\JsonApiClient\Utils\Tests;

use Art4\JsonApiClient\Utils\Manager;

class ManagerTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testSetFactoryReturnsSelf()
	{
		$factory = $this->getMockBuilder('Art4\JsonApiClient\Utils\Factory')
			->getMock();

		$manager = new Manager;

		$this->assertSame($manager, $manager->setFactory($factory));
	}

	/**
	 * @test
	 */
	public function testGetFactoryReturnsFactoryInterface()
	{
		$factory = $this->getMockBuilder('Art4\JsonApiClient\Utils\Factory')
			->getMock();

		$manager = (new Manager)->setFactory($factory);

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryInterface', $manager->getFactory());
	}

	/**
	 * @test
	 */
	public function testGetFactoryWitoutSetReturnsFactoryInterface()
	{
		$manager = new Manager;

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryInterface', $manager->getFactory());
	}
}
