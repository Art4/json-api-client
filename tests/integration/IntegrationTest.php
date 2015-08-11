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

	/**
	 * @test
	 */
	public function testParseCompleteResourceObjectWithMultipleRelationships()
	{
		$string = $this->getJsonString('04_complete_document_with_multiple_relationships.js');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->hasErrors());
		$this->assertFalse($document->hasMeta());
		$this->assertFalse($document->hasJsonapi());
		$this->assertFalse($document->hasLinks());
		$this->assertTrue($document->hasIncluded());
		$this->assertTrue($document->hasData());

		$resources = $document->getData();

		$this->assertTrue(is_array($resources));
		$this->assertTrue(count($resources) === 1);

		$resource = $resources[0];

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);
		$this->assertFalse($resource->hasMeta());
		$this->assertSame($resource->getType(), 'articles');
		$this->assertSame($resource->getId(), '1');
		$this->assertTrue($resource->hasAttributes());
		$this->assertTrue($resource->hasRelationships());
		$this->assertTrue($resource->hasLinks());

		$attributes = $resource->getAttributes();

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->__isset('title'));
		$this->assertSame($attributes->get('title'), 'JSON API paints my bikeshed!');

		$collection = $resource->getRelationships();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertTrue($collection->__isset('author'));
		$this->assertTrue($collection->__isset('comments'));

		$author = $collection->get('author');

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $author);
		$this->assertTrue($author->hasLinks());
		$this->assertTrue($author->hasData());

		$links = $author->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1/relationships/author');
		$this->assertTrue($links->__isset('related'));
		$this->assertSame($links->get('related'), 'http://example.com/articles/1/author');

		$data = $author->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $data);
		$this->assertSame($data->getType(), 'people');
		$this->assertSame($data->getId(), '9');

		$comments = $collection->get('comments');

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $comments);
		$this->assertTrue($comments->hasLinks());
		$this->assertTrue($comments->hasData());

		$links = $comments->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1/relationships/comments');
		$this->assertTrue($links->__isset('related'));
		$this->assertSame($links->get('related'), 'http://example.com/articles/1/comments');

		$data_array = $comments->getData();

		$this->assertTrue(is_array($data_array));
		$this->assertTrue(count($data_array) === 2);

		$identifier = $data_array[0];

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $identifier);
		$this->assertSame($identifier->getType(), 'comments');
		$this->assertSame($identifier->getId(), '5');

		$identifier = $data_array[1];

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $identifier);
		$this->assertSame($identifier->getType(), 'comments');
		$this->assertSame($identifier->getId(), '12');

		$links = $resource->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1');

		$includes = $document->getIncluded();

		$this->assertTrue(is_array($includes));
		$this->assertTrue(count($includes) === 3);

		$include = $includes[0];

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $include);
		$this->assertSame($include->getType(), 'people');
		$this->assertSame($include->getId(), '9');
		$this->assertTrue($include->hasAttributes());
		$this->assertTrue($include->hasLinks());

		$attributes = $include->getAttributes();

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->__isset('first-name'));
		$this->assertSame($attributes->get('first-name'), 'Dan');
		$this->assertTrue($attributes->__isset('last-name'));
		$this->assertSame($attributes->get('last-name'), 'Gebhardt');
		$this->assertTrue($attributes->__isset('twitter'));
		$this->assertSame($attributes->get('twitter'), 'dgeb');

		$links = $include->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), 'http://example.com/people/9');

		$include = $includes[1];

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $include);
		$this->assertSame($include->getType(), 'comments');
		$this->assertSame($include->getId(), '5');
		$this->assertTrue($include->hasAttributes());
		$this->assertTrue($include->hasLinks());

		$attributes = $include->getAttributes();

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->__isset('body'));
		$this->assertSame($attributes->get('body'), 'First!');

		$links = $include->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), 'http://example.com/comments/5');

		$include = $includes[2];

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $include);
		$this->assertSame($include->getType(), 'comments');
		$this->assertSame($include->getId(), '12');
		$this->assertTrue($include->hasAttributes());
		$this->assertTrue($include->hasLinks());

		$attributes = $include->getAttributes();

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->__isset('body'));
		$this->assertSame($attributes->get('body'), 'I like XML better');

		$links = $include->getLinks();

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->__isset('self'));
		$this->assertSame($links->get('self'), 'http://example.com/comments/12');
	}
}
