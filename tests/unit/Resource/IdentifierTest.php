<?php

namespace Art4\JsonApiClient\Resource\Tests;

use Art4\JsonApiClient\Resource\Identifier;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class IdentifierTest extends \PHPUnit_Framework_TestCase
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

		$identifier = new Identifier($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $identifier);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);

		$this->assertSame($identifier->get('type'), 'type');
		$this->assertSame($identifier->get('id'), '789');
		$this->assertFalse($identifier->has('meta'));
		$this->assertSame($identifier->getKeys(), array('type', 'id'));
		$this->assertFalse($identifier->isNull());
		$this->assertTrue($identifier->isIdentifier());
		$this->assertFalse($identifier->isItem());
		$this->assertFalse($identifier->isCollection());
	}

	/**
	 * @test create with object and meta
	 */
	public function testCreateWithObjectAndMeta()
	{
		$object = new \stdClass();
		$object->type = 'types';
		$object->id = 159;
		$object->meta = new \stdClass();

		$identifier = new Identifier($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\ResourceInterface', $identifier);
		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);

		$this->assertSame($identifier->get('type'), 'types');
		$this->assertSame($identifier->get('id'), '159');
		$this->assertTrue($identifier->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $identifier->get('meta'));
		$this->assertSame($identifier->getKeys(), array('type', 'id', 'meta'));
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

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');
		}

		$identifier = new Identifier($object);

		$this->assertTrue(is_string($identifier->get('type')));
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

		if ( gettype($input) === 'object' or gettype($input) === 'array' )
		{
			$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');
		}

		$identifier = new Identifier($object);

		$this->assertTrue(is_string($identifier->get('id')));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithDataproviderThrowsException($input)
	{
		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');

		$identifier = new Identifier($input);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	*
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutTypeThrowsException()
	{
		$object = new \stdClass();
		$object->id = 123;

		$identifier = new Identifier($object);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	*
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutIdThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'type';

		$identifier = new Identifier($object);
	}
}
