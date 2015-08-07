<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Attributes;
use InvalidArgumentException;

class AttributesTest extends \PHPUnit_Framework_TestCase
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

		$attributes = new Attributes($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->__isset('object'));
		$this->assertTrue(is_object($attributes->get('object')));
		$this->assertTrue($attributes->__isset('array'));
		$this->assertTrue(is_array($attributes->get('array')));
		$this->assertTrue($attributes->__isset('string'));
		$this->assertTrue(is_string($attributes->get('string')));
		$this->assertTrue($attributes->__isset('number_int'));
		$this->assertTrue(is_int($attributes->get('number_int')));
		$this->assertTrue($attributes->__isset('number_float'));
		$this->assertTrue(is_float($attributes->get('number_float')));
		$this->assertTrue($attributes->__isset('true'));
		$this->assertTrue($attributes->get('true'));
		$this->assertTrue($attributes->__isset('false'));
		$this->assertFalse($attributes->get('false'));
		$this->assertTrue($attributes->__isset('null'));
		$this->assertNull($attributes->get('null'));
	}

	/**
	 * @test create with empty object
	 */
	public function testCreateWithEmptyObject()
	{
		$object = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', new Attributes($object));
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithTypePropertyThrowsException()
	{
		$object = new \stdClass();
		$object->type = 'posts';

		$attributes = new Attributes($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithIdPropertyThrowsException()
	{
		$object = new \stdClass();
		$object->id = '5';

		$attributes = new Attributes($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithRelationshipsPropertyThrowsException()
	{
		$object = new \stdClass();
		$object->relationships = new \stdClass();

		$attributes = new Attributes($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
	 */
	public function testCreateWithLinksPropertyThrowsException()
	{
		$object = new \stdClass();
		$object->links = new \stdClass();

		$attributes = new Attributes($object);
	}
}
