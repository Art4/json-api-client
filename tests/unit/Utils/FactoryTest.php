<?php

namespace Art4\JsonApiClient\Utils\Tests;

use Art4\JsonApiClient\Utils\Factory;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function testInjectACustomClass()
	{
		$factory = new Factory(array(
			'Default' => 'stdClass',
		));

		$this->assertInstanceOf('Art4\JsonApiClient\Utils\FactoryInterface', $factory);
		$this->assertInstanceOf('stdClass', $factory->make('Default'));
	}

	/**
	 * @test parse throw Exception if input is invalid jsonapi
	 */
	public function testMakeAnUndefindedClassThrowsException()
	{
		$factory = new Factory();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\FactoryException', '"NotExistent" is not a registered class'
		);

		$class = $factory->make('NotExistent');
	}
}
