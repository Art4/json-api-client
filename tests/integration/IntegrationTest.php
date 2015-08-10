<?php

namespace Art4\JsonApiClient\Integration\Tests;

use Art4\JsonApiClient\Utils\Helper;
use InvalidArgumentException;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * returns a json string
	 */
	protected function getJsonString($filename)
	{
		$content = file_get_contents(__DIR__ . '/../files/' . $filename);

		return $content;
	}

	/**
	 * @test
	 */
	public function testParseSimpleResource()
	{
		$string = $this->getJsonString('01_simple_resource.js');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->hasErrors());
		$this->assertFalse($document->hasMeta());
		$this->assertFalse($document->hasJsonapi());
		$this->assertFalse($document->hasLinks());
		$this->assertFalse($document->hasIncluded());
		$this->assertTrue($document->hasData());

		$resource = $document->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);
		$this->assertFalse($resource->hasMeta());
		$this->assertSame($resource->getType(), 'articles');
		$this->assertSame($resource->getId(), '1');
		$this->assertTrue($resource->hasAttributes());
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $resource->getAttributes());
		$this->assertTrue($resource->hasRelationships());
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $resource->getRelationships());
	}

	/**
	 * @test
	 */
	public function testParseSimpleResourceIdentifier()
	{
		$string = $this->getJsonString('02_simple_resource_identifier.js');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->hasErrors());
		$this->assertFalse($document->hasMeta());
		$this->assertFalse($document->hasJsonapi());
		$this->assertFalse($document->hasLinks());
		$this->assertFalse($document->hasIncluded());
		$this->assertTrue($document->hasData());

		$resource = $document->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $resource);
		$this->assertFalse($resource->hasMeta());
		$this->assertSame($resource->getType(), 'articles');
		$this->assertSame($resource->getId(), '1');
	}

	/**
	 * @test
	 */
	public function testParseResourceObject()
	{
		$string = $this->getJsonString('03_resource_object.js');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->hasErrors());
		$this->assertFalse($document->hasMeta());
		$this->assertFalse($document->hasJsonapi());
		$this->assertFalse($document->hasLinks());
		$this->assertFalse($document->hasIncluded());
		$this->assertTrue($document->hasData());

		$resource = $document->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);
		$this->assertFalse($resource->hasMeta());
		$this->assertSame($resource->getType(), 'articles');
		$this->assertSame($resource->getId(), '1');
		$this->assertTrue($resource->hasAttributes());
		$this->assertTrue($resource->hasRelationships());

		$attributes = $resource->getAttributes();

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->__isset('title'));
		$this->assertSame($attributes->get('title'), 'Rails is Omakase');

		$collection = $resource->getRelationships();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertTrue($collection->__isset('author'));

		$author = $collection->get('author');

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $author);
		$this->assertTrue($author->hasLinks());
		$this->assertTrue($author->hasData());

		$links = $author->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), '/articles/1/relationships/author');
		$this->assertTrue($links->__isset('related'));
		$this->assertSame($links->get('related'), '/articles/1/author');

		$data = $author->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $data);
		$this->assertSame($data->getType(), 'people');
		$this->assertSame($data->getId(), '9');
	}
}
