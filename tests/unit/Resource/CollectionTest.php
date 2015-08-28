<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Collection;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create with empty array
	 */
	public function testCreateWithEmptyArray()
	{
		$collection = new Collection(array());

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $collection);

		$this->assertFalse($collection->isNull());
		$this->assertFalse($collection->isIdentifier());
		$this->assertFalse($collection->isItem());
		$this->assertTrue($collection->isCollection());
		$this->assertTrue(count($collection->asArray()) === 0);
		$this->assertSame($collection->getKeys(), array());
		$this->assertFalse($collection->has(0));
	}

	/**
	 * @test create with identifier object
	 */
	public function testCreateWithIdentifier()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->meta = new \stdClass();

		$collection = new Collection(array($object, $object, $object));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);

		$this->assertTrue($collection->isCollection());
		$this->assertTrue( count($collection->asArray()) === 3);
		$this->assertSame($collection->getKeys(), array(0, 1, 2));

		$this->assertTrue($collection->has(0));
		$resource = $collection->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $resource);

		$this->assertTrue($collection->has(1));
		$resource = $collection->get(1);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $resource);

		$this->assertTrue($collection->has(2));
		$resource = $collection->get(2);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $resource);

		$this->assertSame($collection->asArray(), array(
			$collection->get(0),
			$collection->get(1),
			$collection->get(2),
		));

		$this->assertSame($collection->asArray(true), array(
			$collection->get(0)->asArray(true),
			$collection->get(1)->asArray(true),
			$collection->get(2)->asArray(true),
		));
	}

	/**
	 * @test create with item object
	 */
	public function testCreateWithItem()
	{
		$object = new \stdClass();
		$object->type = 'type';
		$object->id = 789;
		$object->attributes = new \stdClass();
		$object->relationships = new \stdClass();
		$object->links = new \stdClass();

		$collection = new Collection(array($object));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);

		$this->assertTrue($collection->isCollection());
		$this->assertTrue( count($collection->asArray()) === 1);
		$this->assertSame($collection->getKeys(), array(0));
		$this->assertTrue($collection->has(0));

		$resource = $collection->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $resource);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutArrayThrowsException($input)
	{
		// Input must be an array
		if ( gettype($input) === 'array' )
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', new Collection($input));

			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$collection = new Collection($input);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutObjectInArrayThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$collection = new Collection(array($input));
	}

	/**
	 * @test get('resources') on an empty collection throws an exception
	 */
	public function testGetResourcesWithEmptyCollectionThrowsException()
	{
		$collection = new Collection(array());

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $collection);

		$this->assertFalse($collection->has(0));

		$this->setExpectedException('Art4\JsonApiClient\Exception\AccessException');

		$collection->get(0);
	}
}
