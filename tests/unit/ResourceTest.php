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

		$this->assertSame($resource->get('type'), 'type');
		$this->assertSame($resource->get('id'), '789');
		$this->assertTrue($resource->has('meta'));
		$this->assertFalse($resource->has('attributes'));
		$this->assertFalse($resource->has('relationships'));
		$this->assertFalse($resource->has('links'));
		$this->assertSame($resource->getKeys(), array('type', 'id', 'meta'));
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

		$this->assertSame($resource->get('type'), 'type');
		$this->assertSame($resource->get('id'), '789');
		$this->assertFalse($resource->has('meta'));
		$this->assertTrue($resource->has('attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $resource->get('attributes'));
		$this->assertTrue($resource->has('relationships'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $resource->get('relationships'));
		$this->assertTrue($resource->has('links'));
		$this->assertInstanceOf('Art4\JsonApiClient\Link', $resource->get('links'));
		$this->assertSame($resource->getKeys(), array('type', 'id', 'attributes', 'relationships', 'links'));
	}
}
