<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Item;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ItemTest extends \PHPUnit_Framework_TestCase
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
		$object->type = 'type';
		$object->id = 789;

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$item->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $item);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $item);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $item);
		$this->assertSame($item->getKeys(), array('type', 'id'));

		$this->assertSame($item->get('type'), 'type');
		$this->assertSame($item->get('id'), '789');
		$this->assertFalse($item->has('meta'));
		$this->assertFalse($item->has('attributes'));
		$this->assertFalse($item->has('relationships'));
		$this->assertFalse($item->has('links'));
		$this->assertFalse($item->isNull());
		$this->assertFalse($item->isIdentifier());
		$this->assertTrue($item->isItem());
		$this->assertFalse($item->isCollection());

		// test get() with not existing key throws an exception
		$this->assertFalse($item->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this resource.'
		);

		$item->get('something');
	}

	/**
	 * @test create with full object
	 */
	public function testCreateWithFullObject()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->meta = new \stdClass();
		$object->attributes = new \stdClass();
		$object->relationships = new \stdClass();
		$object->links = new \stdClass();

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));
		$item->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $item);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $item);

		$this->assertSame($item->get('type'), 'type');
		$this->assertSame($item->get('id'), '789');
		$this->assertTrue($item->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $item->get('meta'));
		$this->assertTrue($item->has('attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\AttributesInterface', $item->get('attributes'));
		$this->assertTrue($item->has('relationships'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollectionInterface', $item->get('relationships'));
		$this->assertTrue($item->has('links'));
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ItemLinkInterface', $item->get('links'));
		$this->assertSame($item->getKeys(), array('type', 'id', 'meta', 'attributes', 'relationships', 'links'));

		$this->assertSame($item->asArray(), array(
			'type' => $item->get('type'),
			'id' => $item->get('id'),
			'meta' => $item->get('meta'),
			'attributes' => $item->get('attributes'),
			'relationships' => $item->get('relationships'),
			'links' => $item->get('links'),
		));

		// Test full array
		$this->assertSame($item->asArray(true), array(
			'type' => $item->get('type'),
			'id' => $item->get('id'),
			'meta' => $item->get('meta')->asArray(true),
			'attributes' => $item->get('attributes')->asArray(true),
			'relationships' => $item->get('relationships')->asArray(true),
			'links' => $item->get('links')->asArray(true),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
		*
	 * The values of the id and type members MUST be strings.
	 */
	public function testTypeCannotBeAnObjectOrArray($input)
	{
		$object = new \stdClass();
		$object->type = $input;
		$object->id = '753';

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource type cannot be an array or object'
			);
		}

		$item->parse($object);

		$this->assertTrue(is_string($item->get('type')));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The values of the id and type members MUST be strings.
	 */
	public function testIdCannotBeAnObjectOrArray($input)
	{
		$object = new \stdClass();
		$object->type = 'posts';
		$object->id = $input;

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException(
				'Art4\JsonApiClient\Exception\ValidationException',
				'Resource id cannot be an array or object'
			);
		}

		$item->parse($object);

		$this->assertTrue(is_string($item->get('id')));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A "resource object" is an object that identifies an individual resource.
	 * A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithDataproviderThrowsException($input)
	{
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Resource has to be an object, "' . gettype($input) . '" given.'
		);

		$item->parse($input);
	}

	/**
	 * @test A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutTypeThrowsException()
	{
		$object = new \stdClass();
		$object->id = 123;

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain a type'
		);

		$item->parse($object);
	}

	/**
	 * @test A "resource object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutIdThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'type';

		$item = new Item($this->manager, $this->getMock('Art4\JsonApiClient\AccessInterface'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'A resource object MUST contain an id'
		);

		$item->parse($object);
	}
}
