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
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithStringThrowsException()
	{
		$document = new Document('');
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithArrayThrowsException()
	{
		$document = new Document(array());
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithIntegerThrowsException()
	{
		$document = new Document(123);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithBooleanThrowsException()
	{
		$document = new Document(true);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A JSON object MUST be at the root of every JSON API request and response containing data.
	 */
	public function testCreateWithNullThrowsException()
	{
		$document = new Document(null);
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

		$datas = $document->getData();

		$this->assertTrue(is_array($datas));
		$this->assertTrue( count($datas) === 1);

		foreach ($datas as $data)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $data);
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

		$datas = $document->getData();

		$this->assertTrue(is_array($datas));
		$this->assertTrue( count($datas) === 1);

		foreach ($datas as $data)
		{
			$this->assertInstanceOf('Art4\JsonApiClient\Resource', $data);
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

		$datas = $document->getData();

		$this->assertTrue(is_array($datas));
		$this->assertTrue( count($datas) === 0);
	}

	/**
	 * @test create with an error array
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
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 */
	public function testCreateWithObjectInErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->errors = new \stdClass();

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 */
	public function testCreateWithStringInErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->errors = 'errors';

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 */
	public function testCreateWithIntegerInErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->errors = 45;

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 */
	public function testCreateWithTrueInErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->errors = true;

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 */
	public function testCreateWithFalseInErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->errors = false;

		$document = new Document($object);
	}

	/**
	 * @expectedException InvalidArgumentException
	 *
	 * A document MUST contain at least one of the following top-level members: errors: an array of error objects
	 */
	public function testCreateWithNullInErrorsThrowsException()
	{
		$object = new \stdClass();
		$object->errors = null;

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
	 * @test create with meta object
	 */
	public function testCreateWithMetaObject()
	{
		$object = new \stdClass();
		$object->meta = new \stdClass();

		$document = new Document($object);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->hasMeta());

		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $document->getMeta());
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
}
