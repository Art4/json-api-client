<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\ResourceIdentifier;
use InvalidArgumentException;

class ResourceIdentifierTest extends \PHPUnit_Framework_TestCase
{
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
	}

	/**
	 * @expectedException InvalidArgumentException
	*
	 * A "resource identifier object" MUST contain type and id members.
	 */
	public function testCreateWithEmptyObjectThrowsException()
	{
		$identifier = new ResourceIdentifier(new \stdClass());
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

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 */
	public function testCreateWithArrayThrowsException()
	{
		$identifier = new ResourceIdentifier(array());
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 */
	public function testCreateWithStringThrowsException()
	{
		$identifier = new ResourceIdentifier('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 */
	public function testCreateWithIntegerThrowsException()
	{
		$identifier = new ResourceIdentifier(123);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 */
	public function testCreateWithTrueThrowsException()
	{
		$identifier = new ResourceIdentifier(true);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 */
	public function testCreateWithFalseThrowsException()
	{
		$identifier = new ResourceIdentifier(false);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A "resource identifier object" is an object that identifies an individual resource.
	 */
	public function testCreateWithNullThrowsException()
	{
		$identifier = new ResourceIdentifier(null);
	}
}
