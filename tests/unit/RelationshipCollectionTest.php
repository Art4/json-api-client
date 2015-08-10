<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\RelationshipCollection;
use InvalidArgumentException;

class RelationshipCollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->author = new \stdClass();
		$object->author->meta = new \stdClass();

		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('hasAttributes')->willReturn(false);

		$collection = new RelationshipCollection($object, $mock);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertTrue($collection->__isset('author'));
		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $collection->get('author'));
	}

	/**
	 * @test create with empty object
	 */
	public function testCreateWithEmptyObject()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('hasAttributes')->willReturn(false);

		$object = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', new RelationshipCollection($object, $mock));
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithTypePropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('hasAttributes')->willReturn(false);

		$object = new \stdClass();
		$object->type = 'posts';

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithIdPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('hasAttributes')->willReturn(false);

		$object = new \stdClass();
		$object->id = '5';

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `attributes`
	 */
	public function testCreateWithRelationshipsPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('hasAttributes')->willReturn(false);

		$object = new \stdClass();
		$object->relationships = new \stdClass();

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `attributes`
	 */
	public function testCreateWithAttributesPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('hasAttributes')->willReturn(false);

		$object = new \stdClass();
		$object->attributes = new \stdClass();

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * In other words, a resource can not have an attribute and relationship with the same name,
	 */
	public function testCreateWithAuthorInRelationshipsAndAttributesThrowsException()
	{
		$map = array(
			array('relationships', false),
		);
		$mock_attributes = $this->getMockBuilder('Art4\JsonApiClient\Attributes')
			->disableOriginalConstructor()
			->getMock();
		$mock_attributes->method('__isset')
			->will($this->returnValueMap($map));

		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource')
			->disableOriginalConstructor()
			->getMock();
		$mock->method('hasAttributes')->willReturn(true);
		$mock->method('getAttributes')->willReturn($mock_attributes);

		$object = new \stdClass();
		$object->relationships = new \stdClass();
		$object->relationships->author = new \stdClass();

		$collection = new RelationshipCollection($object, $mock);
	}
}
