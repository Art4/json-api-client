<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ErrorCollection;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorCollectionTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @setup
	 */
	public function setUp()
	{
		$this->manager = $this->buildManagerMock();
	}

	/**
	 * @test create
	 */
	public function testCreate()
	{
		$errors = array(
			new \stdClass(),
			new \stdClass(),
		);

		$collection = new ErrorCollection($errors, $this->manager);

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
		// Input must be an array with at least one object
		if ( gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Errors array cannot be empty and MUST have at least one object'
			);
		}
		else
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Errors for a collection has to be in an array, "' . gettype($input) . '" given.'
			);
		}

		$collection = new ErrorCollection($input, $this->manager);
	}

	/**
	 * @test get('resources') on an empty collection throws an exception
	 */
	public function testGetErrorWithEmptyCollectionThrowsException()
	{
		$errors = array(
			new \stdClass(),
		);

		$collection = new ErrorCollection($errors, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollection', $collection);

		$this->assertFalse($collection->has(1));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"1" doesn\'t exist in this collection.'
		);

		$collection->get(1);
	}
}
