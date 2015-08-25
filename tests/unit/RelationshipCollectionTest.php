<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\RelationshipCollection;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class RelationshipCollectionTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->author = new \stdClass();
		$object->author->meta = new \stdClass();

		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$collection = new RelationshipCollection($object, $mock);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);
		$this->assertTrue($collection->has('author'));
		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $collection->get('author'));
	}

	/**
	 * @test create with empty object
	 */
	public function testCreateWithEmptyObject()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', new RelationshipCollection($object, $mock));
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithTypePropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();
		$object->type = 'posts';

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithIdPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();
		$object->id = '5';

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `attributes`
	 */
	public function testCreateWithRelationshipsPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();
		$object->relationships = new \stdClass();

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `attributes`
	 */
	public function testCreateWithAttributesPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();
		$object->attributes = new \stdClass();

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * In other words, a resource can not have an attribute and relationship with the same name,
	 */
	public function testCreateWithAuthorInRelationshipsAndAttributesThrowsException()
	{
		$mock_attributes = $this->getMockBuilder('Art4\JsonApiClient\Attributes')
			->disableOriginalConstructor()
			->getMock();
		$mock_attributes->method('has')
			->with($this->equalTo('relationships'))
			->will($this->returnValue(true));

		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();
		$mock->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(true);
		$mock->method('get')
			->with($this->equalTo('attributes'))
			->willReturn($mock_attributes);

		$object = new \stdClass();
		$object->relationships = new \stdClass();
		$object->relationships->author = new \stdClass();

		$collection = new RelationshipCollection($object, $mock);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		// Skip if $input is an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');
		$document = new RelationshipCollection($input, $mock);
	}
}
