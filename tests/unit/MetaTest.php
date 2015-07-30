<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Meta;
use InvalidArgumentException;

class MetaTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test create with object
	 */
	public function testCreateWithObject()
	{
		$object = new \stdClass();
		$object->object = new \stdClass();
		$object->array = array();
		$object->string = 'string';
		$object->number_int = 654;
		$object->number_float = 654.321;
		$object->true = true;
		$object->false = false;
		$object->null = null;

		$meta = new Meta($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $meta);
		$this->assertTrue($meta->__isset('object'));
		$this->assertTrue(is_object($meta->get('object')));
		$this->assertTrue($meta->__isset('array'));
		$this->assertTrue(is_array($meta->get('array')));
		$this->assertTrue($meta->__isset('string'));
		$this->assertTrue(is_string($meta->get('string')));
		$this->assertTrue($meta->__isset('number_int'));
		$this->assertTrue(is_int($meta->get('number_int')));
		$this->assertTrue($meta->__isset('number_float'));
		$this->assertTrue(is_float($meta->get('number_float')));
		$this->assertTrue($meta->__isset('true'));
		$this->assertTrue($meta->get('true'));
		$this->assertTrue($meta->__isset('false'));
		$this->assertFalse($meta->get('false'));
		$this->assertTrue($meta->__isset('null'));
		$this->assertNull($meta->get('null'));
	}

	/**
	 * @test create with empty object
	 */
	public function testCreateWithEmptyObject()
	{
		$meta = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\Meta', new Meta($meta));
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * meta: a meta object that contains non-standard meta-information.
	 */
	public function testCreateWithArrayThrowsException()
	{
		$meta = new Meta(array());
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * meta: a meta object that contains non-standard meta-information.
	 */
	public function testCreateWithStringThrowsException()
	{
		$meta = new Meta('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * meta: a meta object that contains non-standard meta-information.
	 */
	public function testCreateWithIntegerThrowsException()
	{
		$meta = new Meta(123);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * meta: a meta object that contains non-standard meta-information.
	 */
	public function testCreateWithTrueThrowsException()
	{
		$meta = new Meta(true);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * meta: a meta object that contains non-standard meta-information.
	 */
	public function testCreateWithFalseThrowsException()
	{
		$meta = new Meta(false);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * meta: a meta object that contains non-standard meta-information.
	 */
	public function testCreateWithNullThrowsException()
	{
		$meta = new Meta(null);
	}
}
