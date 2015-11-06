<?php

namespace Art4\JsonApiClient\Utils\Tests;

use Art4\JsonApiClient\Utils\Helper;

class HelperTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test parse() with valid JSON API returns Document Object
	 */
	public function testParseWithValidJsonapiReturnsDocument()
	{
		$jsonapi = '{"meta":{}}';

		$this->assertInstanceOf('Art4\JsonApiClient\Document', Helper::parse($jsonapi));
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * @test parse throw Exception if input is invalid jsonapi
	 */
	public function testParseWithInvalidJsonapiThrowsException()
	{
		$invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

		$output = Helper::parse($invalid_jsonapi);
	}

	/**
	 * @expectedException Art4\JsonApiClient\Exception\ValidationException
	 *
	 * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
	 */
	public function testParseWithInvalidJsonThrowsException()
	{
		$invalid_json = 'invalid_json_string';

		$output = Helper::parse($invalid_json);
	}

	/**
	 * @test isValid() with valid JSON API returns true
	 */
	public function testIsValidWithValidJsonapi()
	{
		$jsonapi = '{"meta":{}}';

		$this->assertTrue(Helper::isValid($jsonapi));
	}

	/**
	 * @test isValid() with invalid jsonapi
	 */
	public function testIsValidWithInvalidJsonapi()
	{
		$invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

		$this->assertFalse(Helper::isValid($invalid_jsonapi));
	}

	/**
	 * @test isValid() with invalid json
	 */
	public function testIsValidWithInvalidJson()
	{
		$invalid_json = 'invalid_json_string';

		$this->assertFalse(Helper::isValid($invalid_json));
	}
}
