<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Error;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;

class ErrorTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test create with object returns self
	 */
	public function testCreateWithObjectReturnsSelf()
	{
		$object = new \stdClass();
		$object->id = 'id';
		$object->links = new \stdClass();
		$object->links->about = 'http://example.org/about';
		$object->status = 'status';
		$object->code = 'code';
		$object->title = 'title';
		$object->detail = 'detail';
		$object->source = new \stdClass();
		$object->meta = new \stdClass();

		$error = new Error($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Error', $error);

		$this->assertTrue($error->hasId());
		$this->assertSame($error->getId(), 'id');
		$this->assertTrue($error->hasLinks());
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $error->getLinks());
		$this->assertTrue($error->hasStatus());
		$this->assertSame($error->getStatus(), 'status');
		$this->assertTrue($error->hasCode());
		$this->assertSame($error->getCode(), 'code');
		$this->assertTrue($error->hasTitle());
		$this->assertSame($error->getTitle(), 'title');
		$this->assertTrue($error->hasDetail());
		$this->assertSame($error->getDetail(), 'detail');
		$this->assertTrue($error->hasSource());
		$this->assertInstanceOf('Art4\JsonApiClient\ErrorSource', $error->getSource());
		$this->assertTrue($error->hasMeta());
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $error->getMeta());
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException('InvalidArgumentException');
		$error = new Error($input);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateIdWithoutStringThrowsException($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->id = $input;

		$this->setExpectedException('InvalidArgumentException');
		$error = new Error($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateStatusWithoutStringThrowsException($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->status = $input;

		$this->setExpectedException('InvalidArgumentException');
		$error = new Error($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateCodeWithoutStringThrowsException($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->code = $input;

		$this->setExpectedException('InvalidArgumentException');
		$error = new Error($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateTitleWithoutStringThrowsException($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->title = $input;

		$this->setExpectedException('InvalidArgumentException');
		$error = new Error($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 */
	public function testCreateDetailWithoutStringThrowsException($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->detail = $input;

		$this->setExpectedException('InvalidArgumentException');
		$error = new Error($object);
	}
}
