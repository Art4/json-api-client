<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ErrorCollection;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class ErrorCollectionTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create
	 */
	public function testCreate()
	{
		$errors = array(
			new \stdClass(),
			new \stdClass(),
		);

		$collection = new ErrorCollection($errors);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);

		$this->assertTrue(count($collection->asArray()) === 2);
		$this->assertSame($collection->getKeys(), array(0, 1));

		$this->assertTrue($collection->has(0));
		$error = $collection->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\Error', $error);

		$this->assertTrue($collection->has(1));
		$error = $collection->get(1);

		$this->assertInstanceOf('Art4\JsonApiClient\Error', $error);

		$this->assertSame($collection->asArray(), array(
			$collection->get(0),
			$collection->get(1),
		));

		// Test full array
		$this->assertSame($collection->asArray(true), array(
			$collection->get(0)->asArray(true),
			$collection->get(1)->asArray(true),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutArrayThrowsException($input)
	{
		// Input must be an array
		if ( gettype($input) === 'array' )
		{
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$collection = new ErrorCollection($input);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutObjectInArrayThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$collection = new ErrorCollection(array($input));
	}

	/**
	 * @test get('resources') on an empty collection throws an exception
	 */
	public function testGetErrorWithEmptyCollectionThrowsException()
	{
		$errors = array(
			new \stdClass(),
		);

		$collection = new ErrorCollection($errors);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollection', $collection);

		$this->assertFalse($collection->has(1));

		$this->setExpectedException('Art4\JsonApiClient\Exception\AccessException');

		$collection->get(1);
	}
}
