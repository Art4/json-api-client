<?php

namespace Youthweb\JsonApiClient\Tests;

//use Youthweb\JsonApiClient\Reader;
use InvalidArgumentException;

class ReaderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test parse() with valid JSON API return Document Object
	 */
	public function testParseWithValidJsonapiReturnsDocument()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @expectedException InvalidArgumentException
	 * 
	 * JSON API documents are defined in JavaScript Object Notation (JSON) [RFC4627].
	 */
	public function testParseWithInvalidJsonThrowsException()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
