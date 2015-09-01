<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\RelationshipCollection;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class RelationshipCollectionTest extends \PHPUnit_Framework_TestCase
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
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->author = new \stdClass();
		$object->author->meta = new \stdClass();

		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$item->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$collection = new RelationshipCollection($object, $this->manager, $item);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);
		$this->assertSame($collection->getKeys(), array('author'));

		$this->assertTrue($collection->has('author'));
		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $collection->get('author'));

		$this->assertSame($collection->asArray(), array(
			'author' => $collection->get('author'),
		));

		// Test full array
		$this->assertSame($collection->asArray(true), array(
			'author' => $collection->get('author')->asArray(true),
		));

		// test get() with not existing key throws an exception
		$this->assertFalse($collection->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this relationship collection.'
		);

		$collection->get('something');
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

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', new RelationshipCollection($object, $this->manager, $mock));
	}

	/**
	 * @test
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithTypePropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->expects($this->any())
			->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();
		$object->type = 'posts';

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`'
		);

		$collection = new RelationshipCollection($object, $this->manager, $mock);
	}

	/**
	 * @test
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithIdPropertyThrowsException()
	{
		$mock = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();

		$mock->expects($this->any())
			->method('has')
			->with($this->equalTo('attributes'))
			->will($this->returnValue(false));

		$object = new \stdClass();
		$object->id = '5';

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`'
		);

		$collection = new RelationshipCollection($object, $this->manager, $mock);
	}

	/**
	 * @test
	 *
	 * In other words, a resource can not have an attribute and relationship with the same name,
	 */
	public function testCreateWithAuthorInRelationshipsAndAttributesThrowsException()
	{
		$mock_attributes = $this->getMockBuilder('Art4\JsonApiClient\Attributes')
			->disableOriginalConstructor()
			->getMock();
		$mock_attributes->expects($this->any())
			->method('has')
			->with($this->equalTo('author'))
			->will($this->returnValue(true));

		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\Item')
			->disableOriginalConstructor()
			->getMock();
		$item->expects($this->any())
			->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(true);
		$item->expects($this->any())
			->method('get')
			->with($this->equalTo('attributes'))
			->willReturn($mock_attributes);

		$object = new \stdClass();
		$object->author = new \stdClass();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'"author" property cannot be set because it exists already in parents Resource object.'
		);

		$collection = new RelationshipCollection($object, $this->manager, $item);
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

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Relationships has to be an object, "' . gettype($input) . '" given.'
		);
		$document = new RelationshipCollection($input, $this->manager, $mock);
	}
}
