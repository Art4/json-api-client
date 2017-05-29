<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2016  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\Tests\Unit;

use Art4\JsonApiClient\Document;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\Factory;

class DocumentTest extends \PHPUnit_Framework_TestCase
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
	 * @test create with object returns self
	 */
	public function testCreateWithObjectReturnsSelf()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();
		$object->ignore = 'this property must be ignored';

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $document);
		$this->assertSame($document->getKeys(), array('meta'));
		$this->assertTrue($document->has('meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $document->get('meta'));
		$this->assertFalse($document->has('data'));
		$this->assertFalse($document->has('errors'));
		$this->assertFalse($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));

		$this->assertSame($document->asArray(), array(
			'meta' => $document->get('meta'),
		));

		// Test full array
		$this->assertSame($document->asArray(true), array(
			'meta' => $document->get('meta')->asArray(true),
		));
	}

	/**
	 * @test create with all possible values
	 */
	public function testCreateWithAllPossibleValues()
	{
		$object = new \stdClass();
		$object->data = new \stdClass();
		$object->data->type = 'types';
		$object->data->id = 'id';
		$object->included = array();
		$object->included[0] = new \stdClass();
		$object->included[0]->type = 'types';
		$object->included[0]->id = 'id';
		$object->links = new \stdClass();
		$object->jsonapi = new \stdClass();
		$object->meta = new \stdClass();
		$object->ignore = 'this property must be ignored';

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertSame($document->getKeys(), array('data', 'meta', 'included', 'jsonapi', 'links'));
		$this->assertTrue($document->has('data'));
		$this->assertTrue($document->has('meta'));
		$this->assertFalse($document->has('errors'));
		$this->assertTrue($document->has('jsonapi'));
		$this->assertTrue($document->has('links'));
		$this->assertTrue($document->has('included'));
		$this->assertFalse($document->has('ignore'));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithDataproviderThrowsException($input)
	{
		// Skip if $input is an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		$this->setExpectedException('Art4\JsonApiClient\Exception\ValidationException');
		$document = new Document($this->manager);
		$document->parse($input);
	}

	/**
	 * @test
	 *
	 * A document MUST contain at least one of the following top-level members: data, errors, meta
	 */
	public function testCreateWithoutAnyToplevelMemberThrowsException()
	{
		$object = new \stdClass();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Document MUST contain at least one of the following properties: data, errors, meta'
		);

		$document = new Document($this->manager);
		$document->parse($object);
	}

	/**
	 * @test create with data resource identifier
	 *
	 * A document MUST contain at least one of the following top-level members: data: the document's "primary data"
	 * Primary data MUST be either:
	 * - a single resource object, a single resource identifier object, or null, for requests that target single resources
	 * - an array of resource objects, an array of resource identifier objects, or an empty array ([]), for requests that target resource collections
	 */
	public function testCreateDataWithResourceIdentifier()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;

		$object = new \stdClass();
		$object->data = $data;

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('data'));

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierInterface', $document->get('data'));
	}

	/**
	 * @test create with data resource identifier and meta
	 */
	public function testCreateDataWithResourceIdentifierAndMeta()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;
		$data->meta = new \stdClass();

		$object = new \stdClass();
		$object->data = $data;

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);

		$this->assertTrue($document->has('data'));
		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifierInterface', $document->get('data'));
	}

	/**
	 * @test create with data resource item
	 */
	public function testCreateDataWithResourceItem()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;
		$data->attributes = new \stdClass();
		$data->attributes->title = 'The post title';

		$object = new \stdClass();
		$object->data = $data;

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('data'));

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceItemInterface', $document->get('data'));
	}

	/**
	 * @test create with data object array
	 */
	public function testCreateDataWithResourceCollectionIdentifiers()
	{
		$data_obj = new \stdClass();
		$data_obj->type = 'types';
		$data_obj->id = 5;

		$object = new \stdClass();
		$object->data = array($data_obj);

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('data'));

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceCollectionInterface', $document->get('data'));
	}

	/**
	 * @test create with data object array advanced
	 */
	public function testCreateDataWithResourceCollectionResources()
	{
		$data_obj = new \stdClass();
		$data_obj->type = 'types';
		$data_obj->id = 5;
		$data_obj->attributes = new \stdClass();
		$data_obj->attributes->title = 'The title';

		$object = new \stdClass();
		$object->data = array($data_obj);

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('data'));

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceCollectionInterface', $document->get('data'));
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * Test create with any value in data
	 */
	public function testCreateWithDataproviderInValue($input)
	{
		// Skip if $input is an object
		if ( gettype($input) === 'object' )
		{
			return;
		}

		// Test with empty array in data
		if ( gettype($input) === 'array' )
		{
			$object = new \stdClass();
			$object->data = $input;

			$document = new Document($this->manager);
			$document->parse($object);

			$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
			$this->assertTrue($document->has('data'));

			$collection = $document->get('data');

			$this->assertInstanceOf('Art4\JsonApiClient\ResourceCollectionInterface', $collection);

			return;
		}

		// Test with null in data
		if ( gettype($input) === 'NULL' )
		{
			$object = new \stdClass();
			$object->data = $input;

			$document = new Document($this->manager);
			$document->parse($object);

			$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
			$this->assertTrue($document->has('data'));

			$this->assertInstanceOf('Art4\JsonApiClient\ResourceNullInterface', $document->get('data'));

			return;
		}

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'Data value has to be null or an object, "' . gettype($input) . '" given.'
		);

		$object = new \stdClass();
		$object->data = $input;

		$document = new Document($this->manager);
		$document->parse($object);
	}

	/**
	 * @test create with an errors array
	 */
	public function testCreateWithErrorsArray()
	{
		$object = new \stdClass();
		$object->errors = array(
			new \stdClass(),
			new \stdClass(),
		);

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertSame($document->getKeys(), array('errors'));
		$this->assertTrue($document->has('errors'));

		$this->assertInstanceOf('Art4\JsonApiClient\ErrorCollectionInterface', $document->get('errors'));
	}

	/**
	 * @test The members `data` and `errors` MUST NOT coexist in the same document.
	 */
	public function testCreateWithDataAndErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->data = new \stdClass();
		$object->errors = array();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'The properties `data` and `errors` MUST NOT coexist in Document.'
		);

		$document = new Document($this->manager);
		$document->parse($object);
	}

	/**
	 * @test create with meta object
	 */
	public function testCreateWithMetaObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('meta'));

		$this->assertInstanceOf('Art4\JsonApiClient\MetaInterface', $document->get('meta'));
	}

	/**
	 * @test create with Jsonapi object
	 */
	public function testCreateWithJsonapiObject()
	{
		$object = new \stdClass();

		$object->meta = new \stdClass();
		$object->jsonapi = new \stdClass();

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('jsonapi'));

		$this->assertInstanceOf('Art4\JsonApiClient\JsonapiInterface', $document->get('jsonapi'));
	}

	/**
	 * @test create with link object
	 */
	public function testCreateWithLinkObject()
	{
		$object = new \stdClass();

		$object->meta = new \stdClass();
		$object->links = new \stdClass();

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('links'));

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLinkInterface', $document->get('links'));
	}

	/**
	 * @test create with included objects
	 */
	public function testCreateWithIncludedObjects()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;

		$object = new \stdClass();
		$object->data = $data;
		$object->included = array(
			$data,
			$data,
		);

		$document = new Document($this->manager);
		$document->parse($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('included'));

		$resources = $document->get('included');

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceCollectionInterface', $resources);
	}

	/**
	 * @test If a document does not contain a top-level `data` key, the `included` member MUST NOT be present either.
	 */
	public function testCreateIncludedWithoutDataThrowsException()
	{
		$object = new \stdClass();
		$object->included = new \stdClass();
		$object->meta = new \stdClass();

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\ValidationException',
			'If Document does not contain a `data` property, the `included` property MUST NOT be present either.'
		);

		$document = new Document($this->manager);
		$document->parse($object);
	}

	/**
	 * @test
	 */
	public function testGetOnANonExistingKeyThrowsException()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document($this->manager);
		$document->parse($object);

		$this->setExpectedException(
			'Art4\JsonApiClient\Exception\AccessException',
			'"something" doesn\'t exist in Document.'
		);

		$document->get('something');
	}
}
