<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Document;
use InvalidArgumentException;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test parse() with object returns self
	 */
	public function testParseWithObjectReturnsSelf()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document;

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document->parse($object));
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testParseWithoutObjectThrowsException()
	{
		$string = '';

		$document = new Document;

		$document->parse($string);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: data, errors, meta
	 */
	public function testParseWithoutAnyToplevelMemberThrowsException()
	{
		$object = new \stdClass();

		$document = new Document;

		$document->parse($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * The members `data` and `errors` MUST NOT coexist in the same document.
	 */
	public function testParseWithDataAndErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->data = new \stdClass();
		$object->errors = array();

		$document = new Document;

		$document->parse($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * If a document does not contain a top-level `data` key, the `included` member MUST NOT be present either.
	 */
	public function testParseIncludedWithoutDataThrowsException()
	{
		$object = new \stdClass();
		$object->included = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document;

		$document->parse($object);
	}
}
