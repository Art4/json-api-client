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
		$object->meta = new \stdClass();

		$resource = new Resource($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);

		$this->assertSame($resource->getType(), 'type');
		$this->assertSame($resource->getId(), '789');
		$this->assertTrue($resource->hasMeta());
		$this->assertFalse($resource->hasAttributes());
		$this->assertFalse($resource->hasRelationships());
		$this->assertFalse($resource->hasLinks());
	}

	/**
	 * @test create with full object
	 */
	public function testCreateWithFullObject()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->attributes = new \stdClass();
		$object->relationships = new \stdClass();
		$object->links = new \stdClass();

		$resource = new Resource($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);

		$this->assertSame($resource->getType(), 'type');
		$this->assertSame($resource->getId(), '789');
		$this->assertFalse($resource->hasMeta());
		$this->assertTrue($resource->hasAttributes());
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $resource->getAttributes());
		$this->assertTrue($resource->hasRelationships());
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $resource->getRelationships());
		$this->assertTrue($resource->hasLinks());
		$this->assertInstanceOf('Art4\JsonApiClient\Link', $resource->getLinks());
	}
}
