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

		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$item->method('has')
			->with($this->equalTo('attributes.author'))
			->willReturn(false);

		$collection = new RelationshipCollection($this->manager, $item);
		$collection->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);
		$this->assertSame($collection->getKeys(), array('author'));

		$this->assertTrue($collection->has('author'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipInterface', $collection->get('author'));

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
		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$item->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();

		$collection = new RelationshipCollection($this->manager, $item);
		$collection->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
	}

	/**
	 * @test
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithTypePropertyThrowsException()
	{
		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$item->expects($this->any())
			->method('has')
			->with($this->equalTo('attributes'))
			->willReturn(false);

		$object = new \stdClass();
		$object->type = 'posts';

		$collection = new RelationshipCollection($this->manager, $item);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`'
		);

		$collection->parse($object);
	}

	/**
	 * @test
	 *
	 * Fields for a resource object MUST share a common namespace with each other and with `type` and `id`.
	 */
	public function testCreateWithIdPropertyThrowsException()
	{
		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$item->expects($this->any())
			->method('has')
			->with($this->equalTo('attributes'))
			->will($this->returnValue(false));

		$object = new \stdClass();
		$object->id = '5';

		$collection = new RelationshipCollection($this->manager, $item);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'These properties are not allowed in attributes: `type`, `id`'
		);

		$collection->parse($object);
	}

	/**
	 * @test
	 *
	 * In other words, a resource can not have an attribute and relationship with the same name,
	 */
	public function testCreateWithAuthorInRelationshipsAndAttributesThrowsException()
	{
		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$item->expects($this->any())
			->method('has')
			->with($this->equalTo('attributes.author'))
			->willReturn(true);

		$object = new \stdClass();
		$object->author = new \stdClass();

		$collection = new RelationshipCollection($this->manager, $item);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'"author" property cannot be set because it exists already in parents Resource object.'
		);

		$collection->parse($object);
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

		$item = $this->getMockBuilder('Art4\JsonApiClient\Resource\ItemInterface')
			->getMock();

		$collection = new RelationshipCollection($this->manager, $item);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Relationships has to be an object, "' . gettype($input) . '" given.'
		);

		$collection->parse($input);
	}
}
