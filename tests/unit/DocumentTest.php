<?php

namespace Art4\JsonApiClient\Tests;

use Art4\JsonApiClient\Document;
use InvalidArgumentException;

class DocumentTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @test create with object returns self
	 */
	public function testCreateWithObjectReturnsSelf()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$this->assertInstanceOf('Art4\JsonApiClient\Document', new Document($object));
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

		$this->setExpectedException('InvalidArgumentException');
		$document = new Document($input);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: data, errors, meta
	 */
	public function testCreateWithoutAnyToplevelMemberThrowsException()
	{
		$object = new \stdClass();

		$document = new Document($object);
	}

	/**
	 * @test create with data object
	 *
	 * A document MUST contain at least one of the following top-level members: data: the document's "primary data"
	 * Primary data MUST be either:
	 * - a single resource object, a single resource identifier object, or null, for requests that target single resources
	 * - an array of resource objects, an array of resource identifier objects, or an empty array ([]), for requests that target resource collections
	 */
	public function testCreateWithDataObject()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;

		$object = new \stdClass();
		$object->data = $data;

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasData());

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $document->getData());
	}

	/**
	 * @test create with data object advanced
	 */
	public function testCreateWithDataObjectAdvanced()
	{
		$data = new \stdClass();
		$data->type = 'posts';
		$data->id = 5;
		$data->attributes = new \stdClass();
		$data->attributes->title = 'The post title';

		$object = new \stdClass();
		$object->data = $data;

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasData());

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $document->getData());
	}

	/**
	 * @test create with data null
	 */
	public function testCreateWithDataNull()
	{
		$object = new \stdClass();
		$object->data = null;

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasData());

		$this->assertTrue(is_null($document->getData()));
	}

	/**
	 * @test create with data object array
	 */
	public function testCreateWithDataObjectArray()
	{
		$data_obj = new \stdClass();
		$data_obj->type = 'types';
		$data_obj->id = 5;

		$object = new \stdClass();
		$object->data = array($data_obj);

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasData());

		$resources = $document->getData();

		$this->assertTrue(is_array($resources));
		$this->assertTrue( count($resources) === 1);

		foreach ($resources as $resource)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $resource);
		}
	}

	/**
	 * @test create with data object array advanced
	 */
	public function testCreateWithDataObjectArrayAdvanced()
	{
		$data_obj = new \stdClass();
		$data_obj->type = 'types';
		$data_obj->id = 5;
		$data_obj->attributes = new \stdClass();
		$data_obj->attributes->title = 'The title';

		$object = new \stdClass();
		$object->data = array($data_obj);

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasData());

		$resources = $document->getData();

		$this->assertTrue(is_array($resources));
		$this->assertTrue( count($resources) === 1);

		foreach ($resources as $resource)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);
		}
	}

	/**
	 * @test create with data empty array
	 */
	public function testCreateWithDataEmptyArray()
	{
		$object = new \stdClass();
		$object->data = array();

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasData());

		$resources = $document->getData();

		$this->assertTrue(is_array($resources));
		$this->assertTrue( count($resources) === 0);
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

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasErrors());

		$errors = $document->getErrors();

		$this->assertTrue(is_array($errors));
		$this->assertTrue(count($errors) === 2);

		foreach ($errors as $error)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Error', $error);
		}
	}

	/**
	 * @dataProvider jsonValuesProvider
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 * Error objects MUST be returned as an array keyed by errors
	 */
	public function testCreateWithDataproviderInErrorsThrowsException($input)
	{
		$this->setExpectedException('InvalidArgumentException');

		$object = new \stdClass();
		$object->errors = $input;

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * The members `data` and `errors` MUST NOT coexist in the same document.
	 */
	public function testCreateWithDataAndErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->data = new \stdClass();
		$object->errors = array();

		$document = new Document($object);
	}

	/**
	 * @test create with Jsonapi object
	 */
	public function testCreateWithJsonapiObject()
	{
		$object = new \stdClass();

		$object->meta = new \stdClass();
		$object->jsonapi = new \stdClass();

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasJsonapi());

		$this->assertInstanceOf('Art4\JsonApiClient\Jsonapi', $document->getJsonapi());
	}

	/**
	 * @test create with link object
	 */
	public function testCreateWithLinkObject()
	{
		$object = new \stdClass();

		$object->meta = new \stdClass();
		$object->links = new \stdClass();

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasLinks());

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLink', $document->getLinks());
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
		);

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasIncluded());

		$resources = $document->getIncluded();

		$this->assertTrue(is_array($resources));
		$this->assertTrue( count($resources) === 1);

		foreach ($resources as $resource)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $resource);
		}
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * If a document does not contain a top-level `data` key, the `included` member MUST NOT be present either.
	 */
	public function testCreateIncludedWithoutDataThrowsException()
	{
		$object = new \stdClass();
		$object->included = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document($object);
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
