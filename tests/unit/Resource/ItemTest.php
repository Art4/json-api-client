<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Item;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class ItemTest extends \PHPUnit_Framework_TestCase
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

		$resource = new Item($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $resource);
		$this->assertSame($resource->getKeys(), array('type', 'id', 'meta'));

		$this->assertSame($resource->get('type'), 'type');
		$this->assertSame($resource->get('id'), '789');
		$this->assertTrue($resource->has('meta'));
		$this->assertFalse($resource->has('attributes'));
		$this->assertFalse($resource->has('relationships'));
		$this->assertFalse($resource->has('links'));
		$this->assertFalse($resource->isNull());
		$this->assertFalse($resource->isIdentifier());
		$this->assertTrue($resource->isItem());
		$this->assertFalse($resource->isCollection());
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

		$resource = new Item($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);

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

		$this->assertSame($resource->asArray(), array(
			'type' => $resource->get('type'),
			'id' => $resource->get('id'),
			'attributes' => $resource->get('attributes'),
			'relationships' => $resource->get('relationships'),
			'links' => $resource->get('links'),
		));
	}
}
