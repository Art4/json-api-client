<?php

namespace Youthweb\JsonApiClient\Tests;

use Youthweb\JsonApiClient\Client;
use InvalidArgumentException;

class ClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test parse() with valid JSON API returns Document Object
	 */
	public function testParseWithValidJsonapiReturnsDocument()
	{
		$jsonapi = '{"meta":{}}';

		$this->assertInstanceOf('Youthweb\JsonApiClient\Document', Client::parse($jsonapi));
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * @test parse throw Exception if input is invalid jsonapi
	 */
	public function testParseWithInvalidJsonapiThrowsException()
	{
		$invalid_jsonapi = '["This is valid JSON", "but invalid JSON API"]';

		$output = Client::parse($invalid_jsonapi);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
	 */
	public function testParseWithInvalidJsonThrowsException()
	{
		$invalid_json = 'invalid_json_string';

		$output = Client::parse($invalid_json);
	}
}
