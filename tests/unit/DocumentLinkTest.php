<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\DocumentLink;
use InvalidArgumentException;

class DocumentLinkTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test only 'about' property' can exist
	 *
	 * The top-level links object MAY contain the following members:
	 * - self: the link that generated the current response document.
	 * - related: a related resource link when the primary data represents a resource relationship.
	 * - pagination links for the primary data.
	 */
	public function testOnlySelfRelatedPAginationPropertiesExists()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->related = 'http://example.org/related';
		$object->pagination = new \stdClass();
		$object->ignore = 'http://example.org/should-be-ignored';

		$link = new DocumentLink($object);

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLink', $link);

		$this->assertFalse($link->__isset('ignore'));
		$this->assertTrue($link->__isset('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->__isset('related'));
		$this->assertSame($link->get('related'), 'http://example.org/related');
		$this->assertTrue($link->hasPagination());
		$this->assertInstanceOf('Art4\JsonApiClient\PaginationLink', $link->getPagination());
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * self: the link that generated the current response document.
	 */
	public function testSelfMustBeAString($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->self = $input;

		$this->setExpectedException('InvalidArgumentException');

		$link = new DocumentLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * related: a related resource link when the primary data represents a resource relationship.
	 * If present, a related resource link MUST reference a valid URL
	 */
	public function testRelatedMustBeAString($input)
	{
		// Input must be a string
		if ( gettype($input) === 'string' )
		{
			return;
		}

		$object = new \stdClass();
		$object->related = $input;

		$this->setExpectedException('InvalidArgumentException');

		$link = new DocumentLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * pagination links for the primary data.
	 */
	public function testPaginationMustBeAnObject($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$object = new \stdClass();
		$object->pagination = $input;

		$this->setExpectedException('InvalidArgumentException');

		$link = new DocumentLink($object);
	}

	/**
	 * Json Values Provider
	 *
	 * @see http://json.org/
	 */
	public function jsonValuesProvider()
	{
		return array(
		array(new \stdClass()),
		array(array()),
		array('string'),
		array(456),
		array(true),
		array(false),
		array(null),
		);
	}
}
