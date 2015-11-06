<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\RelationshipLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class RelationshipLinkTest extends \PHPUnit_Framework_TestCase
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
		$object->first = 'http://example.org/first';
		$object->last = 'http://example.org/last';
		$object->prev = 'http://example.org/prev';
		$object->next = 'http://example.org/next';
		$object->ignore = 'http://example.org/should-be-ignored';

		$link = new RelationshipLink($object, $this->manager);

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $link);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
		$this->assertSame($link->getKeys(), array('self', 'related', 'first', 'last', 'prev', 'next'));

		$this->assertFalse($link->has('ignore'));
		$this->assertTrue($link->has('self'));
		$this->assertSame($link->get('self'), 'http://example.org/self');
		$this->assertTrue($link->has('related'));
		$this->assertSame($link->get('related'), 'http://example.org/related');
		$this->assertTrue($link->has('first'));
		$this->assertSame($link->get('first'), 'http://example.org/first');
		$this->assertTrue($link->has('last'));
		$this->assertSame($link->get('last'), 'http://example.org/last');
		$this->assertTrue($link->has('prev'));
		$this->assertSame($link->get('prev'), 'http://example.org/prev');
		$this->assertTrue($link->has('next'));
		$this->assertSame($link->get('next'), 'http://example.org/next');

		$this->assertSame($link->asArray(), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'first' => $link->get('first'),
			'last' => $link->get('last'),
			'last' => $link->get('last'),
			'prev' => $link->get('prev'),
			'next' => $link->get('next'),
		));

		// Test full array
		$this->assertSame($link->asArray(true), array(
			'self' => $link->get('self'),
			'related' => $link->get('related'),
			'first' => $link->get('first'),
			'last' => $link->get('last'),
			'prev' => $link->get('prev'),
			'next' => $link->get('next'),
		));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * links: a links object containing at least one of the following:
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
			'RelationshipLink has to be an object, "' . gettype($input) . '" given.'
		);

		$link = new RelationshipLink($input, $this->manager);
	}

	/**
	 * @test object contains at least one of the following: self, related
	 */
	public function testCreateWithoutSelfAndRelatedPropertiesThrowsException()
	{
		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'RelationshipLink has to be at least a "self" or "related" link'
		);

		$object = new \stdClass();
		$object->first = 'http://example.org/first';
		$object->next = 'http://example.org/next';

		$link = new RelationshipLink($object, $this->manager);
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

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'property "self" has to be a string, "' . gettype($input) . '" given.'
		);

		$link = new RelationshipLink($object, $this->manager);
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

		$link = new RelationshipLink($object, $this->manager);
	}

	/**
	 * @test
	 */
	public function testGetOnANonExistingKeyThrowsException()
	{
		$object = new \stdClass();
		$object->self = 'http://example.org/self';
		$object->related = 'http://example.org/related';

		$link = new RelationshipLink($object, $this->manager);

		$this->assertFalse($link->has('something'));

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in this object.'
		);

		$link->get('something');
	}
}
