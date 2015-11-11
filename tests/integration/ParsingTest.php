<?php

namespace Art4\JsonApiClient\Integration\Tests;

use Art4\JsonApiClient\Utils\Helper;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ParsingTest extends \PHPUnit_Framework_TestCase
{
	use HelperTrait;

	/**
	 * @test
	 */
	public function testParseSimpleResource()
	{
		$string = $this->getJsonString('01_simple_resource.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->has('error'));
		$this->assertFalse($document->has('meta'));
		$this->assertFalse($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));
		$this->assertTrue($document->has('data'));

		$resource = $document->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
		$this->assertFalse($resource->has('meta'));
		$this->assertSame($resource->get('type'), 'articles');
		$this->assertSame($resource->get('id'), '1');
		$this->assertTrue($resource->has('attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $resource->get('attributes'));
		$this->assertTrue($resource->has('relationships'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $resource->get('relationships'));

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}

	/**
	 * @test
	 */
	public function testParseSimpleResourceIdentifier()
	{
		$string = $this->getJsonString('02_simple_resource_identifier.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->has('errors'));
		$this->assertFalse($document->has('meta'));
		$this->assertFalse($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));
		$this->assertTrue($document->has('data'));

		$resource = $document->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $resource);
		$this->assertFalse($resource->has('meta'));
		$this->assertSame($resource->get('type'), 'articles');
		$this->assertSame($resource->get('id'), '1');

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}

	/**
	 * @test
	 */
	public function testParseResourceObject()
	{
		$string = $this->getJsonString('03_resource_object.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->has('errors'));
		$this->assertFalse($document->has('meta'));
		$this->assertFalse($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertFalse($document->has('included'));
		$this->assertTrue($document->has('data'));

		$resource = $document->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
		$this->assertFalse($resource->has('meta'));
		$this->assertSame($resource->get('type'), 'articles');
		$this->assertSame($resource->get('id'), '1');
		$this->assertTrue($resource->has('attributes'));
		$this->assertTrue($resource->has('relationships'));

		$attributes = $resource->get('attributes');

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->has('title'));
		$this->assertSame($attributes->get('title'), 'Rails is Omakase');

		$collection = $resource->get('relationships');

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertTrue($collection->has('author'));

		$author = $collection->get('author');

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $author);
		$this->assertTrue($author->has('links'));
		$this->assertTrue($author->has('data'));

		$links = $author->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), '/articles/1/relationships/author');
		$this->assertTrue($links->has('related'));
		$this->assertSame($links->get('related'), '/articles/1/author');

		$data = $author->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $data);
		$this->assertSame($data->get('type'), 'people');
		$this->assertSame($data->get('id'), '9');

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}

	/**
	 * @test
	 */
	public function testParseCompleteResourceObjectWithMultipleRelationships()
	{
		$string = $this->getJsonString('04_complete_document_with_multiple_relationships.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->has('errors'));
		$this->assertFalse($document->has('meta'));
		$this->assertFalse($document->has('jsonapi'));
		$this->assertFalse($document->has('links'));
		$this->assertTrue($document->has('included'));
		$this->assertTrue($document->has('data'));

		$resources = $document->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $resources);
		$this->assertTrue($resources->isCollection());

		$this->assertTrue(count($resources->asArray()) === 1);
		$this->assertSame($resources->getKeys(), array(0));

		$this->assertTrue($resources->has(0));
		$resource = $resources->get(0);

		$this->assertFalse($resource->has('meta'));
		$this->assertSame($resource->get('type'), 'articles');
		$this->assertSame($resource->get('id'), '1');
		$this->assertTrue($resource->has('attributes'));
		$this->assertTrue($resource->has('relationships'));
		$this->assertTrue($resource->has('links'));

		$attributes = $resource->get('attributes');

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->has('title'));
		$this->assertSame($attributes->get('title'), 'JSON API paints my bikeshed!');

		$collection = $resource->get('relationships');

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $collection);
		$this->assertTrue($collection->has('author'));
		$this->assertTrue($collection->has('comments'));

		$author = $collection->get('author');

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $author);
		$this->assertTrue($author->has('links'));
		$this->assertTrue($author->has('data'));

		$links = $author->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1/relationships/author');
		$this->assertTrue($links->has('related'));
		$this->assertSame($links->get('related'), 'http://example.com/articles/1/author');

		$data = $author->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $data);
		$this->assertSame($data->get('type'), 'people');
		$this->assertSame($data->get('id'), '9');

		$comments = $collection->get('comments');

		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $comments);
		$this->assertTrue($comments->has('links'));
		$this->assertTrue($comments->has('data'));

		$links = $comments->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1/relationships/comments');
		$this->assertTrue($links->has('related'));
		$this->assertSame($links->get('related'), 'http://example.com/articles/1/comments');

		$data_array = $comments->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\IdentifierCollection', $data_array);
		$this->assertCount(2, $data_array->getKeys());

		$identifier = $data_array->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);
		$this->assertSame($identifier->get('type'), 'comments');
		$this->assertSame($identifier->get('id'), '5');

		$identifier = $data_array->get(1);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);
		$this->assertSame($identifier->get('type'), 'comments');
		$this->assertSame($identifier->get('id'), '12');

		$links = $resource->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1');

		$includes = $document->get('included');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $includes);
		$this->assertSame($includes->getKeys(), array(0, 1, 2));

		$this->assertTrue($includes->has(0));
		$include = $includes->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $include);
		$this->assertSame($include->get('type'), 'people');
		$this->assertSame($include->get('id'), '9');
		$this->assertTrue($include->has('attributes'));
		$this->assertTrue($include->has('links'));

		$attributes = $include->get('attributes');

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->has('first-name'));
		$this->assertSame($attributes->get('first-name'), 'Dan');
		$this->assertTrue($attributes->has('last-name'));
		$this->assertSame($attributes->get('last-name'), 'Gebhardt');
		$this->assertTrue($attributes->has('twitter'));
		$this->assertSame($attributes->get('twitter'), 'dgeb');

		$links = $include->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/people/9');

		$this->assertTrue($includes->has(1));
		$include = $includes->get(1);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $include);
		$this->assertSame($include->get('type'), 'comments');
		$this->assertSame($include->get('id'), '5');
		$this->assertTrue($include->has('attributes'));
		$this->assertTrue($include->has('links'));

		$attributes = $include->get('attributes');

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->has('body'));
		$this->assertSame($attributes->get('body'), 'First!');

		$links = $include->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/comments/5');

		$this->assertTrue($includes->has(2));
		$include = $includes->get(2);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $include);
		$this->assertSame($include->get('type'), 'comments');
		$this->assertSame($include->get('id'), '12');
		$this->assertTrue($include->has('attributes'));
		$this->assertTrue($include->has('links'));

		$attributes = $include->get('attributes');

		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $attributes);
		$this->assertTrue($attributes->has('body'));
		$this->assertSame($attributes->get('body'), 'I like XML better');

		$links = $include->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/comments/12');

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}

	/**
	 * @test
	 */
	public function testParsePaginationExample()
	{
		$string = $this->getJsonString('06_pagination_example.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('data'));
		$this->assertTrue($document->has('links'));
		$this->assertTrue($document->has('meta'));

		$resource_collection = $document->get('data');

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $resource_collection);
		$this->assertTrue($resource_collection->has(0));
		$this->assertFalse($resource_collection->has(1));

		$resource = $resource_collection->get(0);

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $resource);
		$this->assertFalse($resource->has('meta'));
		$this->assertSame($resource->get('type'), 'articles');
		$this->assertSame($resource->get('id'), '3');
		$this->assertTrue($resource->has('attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $resource->get('attributes'));

		$attributes = $resource->get('attributes');

		$this->assertTrue($attributes->has('title'));
		$this->assertSame($attributes->get('title'), 'JSON API paints my bikeshed!');
		$this->assertTrue($attributes->has('body'));
		$this->assertSame($attributes->get('body'), 'The shortest article. Ever.');
		$this->assertTrue($attributes->has('created'));
		$this->assertSame($attributes->get('created'), '2015-05-22T14:56:29.000Z');
		$this->assertTrue($attributes->has('updated'));
		$this->assertSame($attributes->get('updated'), '2015-05-22T14:56:28.000Z');

		$links = $document->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\DocumentLink', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles?page[number]=3&page[size]=1');
		$this->assertTrue($links->has('first'));
		$this->assertSame($links->get('first'), 'http://example.com/articles?page[number]=1&page[size]=1');
		$this->assertTrue($links->has('prev'));
		$this->assertSame($links->get('prev'), 'http://example.com/articles?page[number]=2&page[size]=1');
		$this->assertTrue($links->has('next'));
		$this->assertSame($links->get('next'), 'http://example.com/articles?page[number]=4&page[size]=1');
		$this->assertTrue($links->has('last'));
		$this->assertSame($links->get('last'), 'http://example.com/articles?page[number]=13&page[size]=1');

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}

	/**
	 * @test
	 */
	public function testParseRelationshipExample()
	{
		$string = $this->getJsonString('07_relationship_example.json');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertTrue($document->has('data'));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Collection', $document->get('data'));
		$this->assertTrue($document->has('data.0'));
		$this->assertFalse($document->has('data.1'));

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Item', $document->get('data.0'));
		$this->assertFalse($document->has('data.0.meta'));
		$this->assertSame($document->get('data.0.type'), 'articles');
		$this->assertSame($document->get('data.0.id'), '1');
		$this->assertTrue($document->has('data.0.attributes'));
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes', $document->get('data.0.attributes'));

		$this->assertTrue($document->has('data.0.attributes.title'));
		$this->assertSame($document->get('data.0.attributes.title'), 'JSON API paints my bikeshed!');

		$this->assertTrue($document->has('data.0.relationships'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection', $document->get('data.0.relationships'));

		$this->assertTrue($document->has('data.0.relationships.comments'));
		$this->assertInstanceOf('Art4\JsonApiClient\Relationship', $document->get('data.0.relationships.comments'));

		$this->assertTrue($document->has('data.0.relationships.comments.links'));
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipLink', $document->get('data.0.relationships.comments.links'));

		$this->assertTrue($document->has('data.0.relationships.comments.links.custom'));
		$this->assertSame($document->get('data.0.relationships.comments.links.custom'), 'http://example.com/articles/1/custom');
		$this->assertTrue($document->has('data.0.relationships.comments.links.self'));
		$this->assertSame($document->get('data.0.relationships.comments.links.self'), 'http://example.com/articles/1/relationships/comments');
		$this->assertTrue($document->has('data.0.relationships.comments.links.first'));
		$this->assertSame($document->get('data.0.relationships.comments.links.first'), 'http://example.com/articles/1/comments?page=1');
		$this->assertTrue($document->has('data.0.relationships.comments.links.last'));
		$this->assertSame($document->get('data.0.relationships.comments.links.last'), 'http://example.com/articles/1/comments?page=10');
		$this->assertTrue($document->has('data.0.relationships.comments.links.prev'));
		$this->assertSame($document->get('data.0.relationships.comments.links.prev'), 'http://example.com/articles/1/comments?page=1');
		$this->assertTrue($document->has('data.0.relationships.comments.links.next'));
		$this->assertSame($document->get('data.0.relationships.comments.links.next'), 'http://example.com/articles/1/comments?page=2');

		$this->assertTrue($document->has('data.0.relationships.comments.links.related'));
		$this->assertInstanceOf('Art4\JsonApiClient\Link', $document->get('data.0.relationships.comments.links.related'));

		$this->assertTrue($document->has('data.0.relationships.comments.links.related.href'));
		$this->assertSame($document->get('data.0.relationships.comments.links.related.href'), 'http://example.com/articles/1/comments');

		$this->assertTrue($document->has('data.0.relationships.comments.links.related.meta'));
		$this->assertInstanceOf('Art4\JsonApiClient\Meta', $document->get('data.0.relationships.comments.links.related.meta'));

		$this->assertTrue($document->has('data.0.relationships.comments.links.related.meta.count'));
		$this->assertSame($document->get('data.0.relationships.comments.links.related.meta.count'), 10);

		// Test full array
		$this->assertEquals(json_decode($string, true), $document->asArray(true));
	}
}
