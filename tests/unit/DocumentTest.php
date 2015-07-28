<?php

namespace Youthweb\JsonApiClient\Tests;

//use Youthweb\JsonApiClient\Document;
use InvalidArgumentException;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test parse() with object returns self
	 */
	public function testParseWithObjectReturnsSelf()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @test parse() with empty string returns self
	 */
	public function testParseWithEmptyStringReturnsSelf()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @expectedException InvalidArgumentException
	 * 
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testParseWithoutObjectThrowsException()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @expectedException InvalidArgumentException
	 * 
	 * A document MUST contain at least one of the following top-level members: data, errors, meta
	 */
	public function testParseWithoutAnyToplevelMemberThrowsException()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @expectedException InvalidArgumentException
	 * 
	 * The members `data` and `errors` MUST NOT coexist in the same document.
	 */
	public function testParseWithDataAndErrorsThrowsException()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @expectedException InvalidArgumentException
	 * 
	 * If a document does not contain a top-level `data` key, the `included` member MUST NOT be present either.
	 */
	public function testParseIncludedWithoutDataThrowsException()
	{
		//$this->assertTrue(TRUE, 'This should already work.');
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
