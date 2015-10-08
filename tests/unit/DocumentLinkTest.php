<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\DocumentLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class DocumentLinkTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @setup
	 */
	public function setUp()
	{
		$this->manager = $this->buildManagerMock();
	}

	/**
	 * @test only 'about' property' can exist
	 *
	 * The top-level links object MAY contain the following members:
	 * - self: the link that generated the current response document.
	 * - related: a related resource link when the primary data represents a resource relationship.
	 * - pagination links for the primary data.
	 */
	public function testOnlySelfRelatedPaginationPropertiesExists()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->related = 'http://example.org/related';
		$object->pagination = new \stdClass();
		$object->ignore = 'http://example.org/should-be-ignored';

		$link = new DocumentLink($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('self', 'related', 'pagination'));

		$this->assertFalse($link->has('ignore'));
		$this->assertTrue($link->has('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->has('related'));
		$this->assertSame($link->get('related'), 'http://example.org/related');
		$this->assertTrue($link->has('pagination'));
		$this->assertInstanceOf('Art4\JsonApiClient\Pagination', $link->get('pagination'));

		$this->assertSame($link->asArray(), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'pagination' => $link->get('pagination'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'pagination' => $link->get('pagination')->asArray(true),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * links: a links object related to the primary data.
	 */
	public function testCreateWithoutObjectThrowsException($input)
	{
		// Input must be an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'DocumentLink has to be an object, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($input, $this->manager);
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

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "self" has to be a string, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
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

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "related" has to be a string, "' . gettype($input) . '" given.'
		);

		$link = new DocumentLink($object, $this->manager);
	}
}
