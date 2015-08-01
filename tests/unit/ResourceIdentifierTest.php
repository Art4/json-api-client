<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ResourceIdentifier;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;
use InvalidArgumentException;

class ResourceIdentifierTest extends \PHPUnit_Framework_TestCase
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

		$identifier = new ResourceIdentifier($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $identifier);

		$this->assertSame($identifier->getType(), 'type');
		$this->assertSame($identifier->getId(), '789');
		$this->assertFalse($identifier->hasMeta());
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

		$identifier = new ResourceIdentifier($object);

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $identifier);

		$this->assertSame($identifier->getType(), 'types');
		$this->assertSame($identifier->getId(), '159');
		$this->assertTrue($identifier->hasMeta());
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $identifier->getMeta());
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
			$this->setExpectedException('InvalidArgumentException');
		}

		$identifier = new ResourceIdentifier($object);

		$this->assertTrue(is_string($identifier->getType()));
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
			$this->setExpectedException('InvalidArgumentException');
		}

		$identifier = new ResourceIdentifier($object);

		$this->assertTrue(is_string($identifier->getId()));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithDataproviderThrowsException($input)
	{
		$this->setExpectedException('InvalidArgumentException');

		$identifier = new ResourceIdentifier($input);
	}

	/**
	 * @expectedException InvalidArgumentException
	*
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutTypeThrowsException()
	{
		$object = new \stdClass();
		$object->id = 123;

		$identifier = new ResourceIdentifier($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	*
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithObjectWithoutIdThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'type';

		$identifier = new ResourceIdentifier($object);
	}
}
