<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Relationship;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class RelationshipTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create with object returns self
	 */
	public function testCreateWithObjectReturnsSelf()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', new Relationship($object));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * The value of the relationships key MUST be an object (a "relationships object").
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		// Skip if $input is an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');
		$relationship = new Relationship($input);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * A "relationship object" MUST contain at least one of the following: links, data, meta
	 */
	public function testCreateWithoutLinksDataMetaPropertiesThrowsException()
	{
		$object = new \stdClass();
		$object->foo = 'bar';

		$relationship = new Relationship($object);
	}

	/**
	 * @test create with link object
	 */
	public function testCreateWithLinksObject()
	{
		$object = new \stdClass();
		$object->links = new \stdClass();
		$object->links->self = 'http://example.org/self';

		$relationship = new Relationship($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
		$this->assertSame($relationship->getKeys(), array('links'));
		$this->assertTrue($relationship->has('links'));

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $relationship->get('links'));
	}

	/**
	 * @test create with data object
	 *
	 * data: resource linkage, see http://jsonapi.org/format/#document-resource-object-linkage
	 */
	public function testCreateWithDataObject()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;

		$object = new \stdClass();
		$object->data = $data;

		$relationship = new Relationship($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
		$this->assertSame($relationship->getKeys(), array('data'));
		$this->assertTrue($relationship->has('data'));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $relationship->get('data'));
	}

	/**
	 * @test create with data null
	 */
	public function testCreateWithDataNull()
	{
		$object = new \stdClass();
		$object->data = null;

		$relationship = new Relationship($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
		$this->assertSame($relationship->getKeys(), array('data'));
		$this->assertTrue($relationship->has('data'));

		$this->assertTrue(is_null($relationship->get('data')));
	}

	/**
	 * @test create with data object array
	 */
	public function testCreateWithDataObjectArray()
	{
		$data_obj = new \stdClass();
		$data_obj->type = 'types';
		$data_obj->id = 5;

		$object = new \stdClass();
		$object->data = array($data_obj);

		$relationship = new Relationship($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
		$this->assertSame($relationship->getKeys(), array('data'));
		$this->assertTrue($relationship->has('data'));

		$resources = $relationship->get('data');

		$this->assertTrue(is_array($resources));
		$this->assertTrue(count($resources) === 1);

		foreach ($resources as $resource)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $resource);
		}
	}

	/**
	 * @test create with data empty array
	 */
	public function testCreateWithDataEmptyArray()
	{
		$object = new \stdClass();
		$object->data = array();

		$relationship = new Relationship($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
		$this->assertSame($relationship->getKeys(), array('data'));
		$this->assertTrue($relationship->has('data'));

		$resources = $relationship->get('data');

		$this->assertTrue(is_array($resources));
		$this->assertTrue(count($resources) === 0);
	}

	/**
	 * @test create with meta object
	 */
	public function testCreateWithMetaObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$relationship = new Relationship($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $relationship);
		$this->assertSame($relationship->getKeys(), array('meta'));
		$this->assertTrue($relationship->has('meta'));

		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $relationship->get('meta'));
	}
}
