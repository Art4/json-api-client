<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Resource;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;
use InvalidArgumentException;

class ResourceTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->links = new \stdClass();

		$resource = new Resource($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);

		$this->assertSame($resource->getType(), 'type');
		$this->assertSame($resource->getId(), '789');
		$this->assertTrue($resource->hasLinks());
		$this->assertFalse($resource->hasMeta());
	}

	/**
	 * @test create with object and attributes
	 */
	public function testCreateWithObjectAndAttributes()
	{
		$object = new \stdClass();
		$object->type = 'types';
		$object->id = 159;
		$object->attributes = new \stdClass();

		$resource = new Resource($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $resource);

		$this->assertSame($resource->getType(), 'types');
		$this->assertSame($resource->getId(), '159');
		$this->assertTrue($resource->hasAttributes());
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $resource->getAttributes());
	}
}
