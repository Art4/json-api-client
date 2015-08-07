<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\RelationshipLink;
use Art4\JsonApiClient\Tests\Fixtures\JsonValueTrait;
use InvalidArgumentException;

class RelationshipLinkTest extends \PHPUnit_Framework_TestCase
{
	use JsonValueTrait;

	/**
	 * @test only self, related and pagination property can exist
	 *
	 * links: a links object containing at least one of the following:
	 * - self: a link for the relationship itself (a "relationship link"). This link allows
	 *   the client to directly manipulate the relationship. For example, it would allow a
	 *   client to remove an author from an article without deleting the people resource itself.
	 * - related: a related resource link
	 *
	 * A relationship object that represents a to-many relationship MAY also contain pagination
	 * links under the links member, as described below.
	 */
	public function testOnlySelfRelatedPaginationPropertiesExists()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->related = 'http://example.org/related';
		$object->pagination = new \stdClass();
		$object->ignore = 'http://example.org/should-be-ignored';

		$link = new RelationshipLink($object);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);

		$this->assertFalse($link->__isset('ignore'));
		$this->assertTrue($link->__isset('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->__isset('related'));
		$this->assertSame($link->get('related'), 'http://example.org/related');
		$this->assertTrue($link->hasPagination());
		$this->assertInstanceOf('Art4\JsonApiClient\PaginationLink', $link->getPagination());
	}

	/**
	 * @test object contains at least one of the following: self, related
	 */
	public function testCreateWithoutSelfAndRelatedPropertiesThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$object = new \stdClass();
		$object->pagination = new \stdClass();

		$link = new RelationshipLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * self: a link for the relationship itself (a "relationship link").
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

		$link = new RelationshipLink($object);
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

		$link = new RelationshipLink($object);
	}

	/**
	 * @dataProvider jsonValuesProvider
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

		$link = new RelationshipLink($object);
	}
}
