<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\NullResource;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class NullTest extends \PHPUnit_Framework_TestCase
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
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithDataProvider($input)
	{
		$resource = new NullResource($input, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\NullResource', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $resource);

		$this->assertFalse($resource->has('something'));
		$this->assertSame($resource->getKeys(), array());

		$this->assertTrue($resource->isNull());
		$this->assertFalse($resource->isIdentifier());
		$this->assertFalse($resource->isItem());
		$this->assertFalse($resource->isCollection());

		$this->assertSame($resource->asArray(), null);

		// Test full array
		$this->assertSame($resource->asArray(true), null);
	}

	/**
	 * @test get throws Exception
	 */
	public function testGetThrowsException()
	{
		$this->setExpectedException('Art4\JsonApiClient\Exception\AccessException', 'A NullResource has no values.');

		$resource = new NullResource(null, $this->manager);
		$resource->get('something');
	}
}
