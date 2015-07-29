<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Document;
use InvalidArgumentException;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test create with object returns self
	 */
	public function testCreateWithObjectReturnsSelf()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\Document', new Document($object));
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithoutObjectThrowsException()
	{
		$string = '';

		$document = new Document($string);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: data, errors, meta
	 */
	public function testCreateWithoutAnyToplevelMemberThrowsException()
	{
		$object = new \stdClass();

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * The members `data` and `errors` MUST NOT coexist in the same document.
	 */
	public function testCreateWithDataAndErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->data = new \stdClass();
		$object->errors = array();

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * If a document does not contain a top-level `data` key, the `included` member MUST NOT be present either.
	 */
	public function testCreateIncludedWithoutDataThrowsException()
	{
		$object = new \stdClass();
		$object->included = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document($object);
	}
}
