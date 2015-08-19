<?php

namespace Art4\JsonApiClient\Integration\Tests;

use Art4\JsonApiClient\Utils\Helper;

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
	}

	/**
	 * @test
	 */
	public function testParseSimpleResourceIdentifier()
	{
		$string = $this->getJsonString('02_simple_resource_identifier.js');
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
	}

	/**
	 * @test
	 */
	public function testParseResourceObject()
	{
		$string = $this->getJsonString('03_resource_object.js');
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
	}

	/**
	 * @test
	 */
	public function testParseCompleteResourceObjectWithMultipleRelationships()
	{
		$string = $this->getJsonString('04_complete_document_with_multiple_relationships.js');
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

		$resource = $resources->asArray()[0];

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

		$this->assertTrue(is_array($data_array));
		$this->assertTrue(count($data_array) === 2);

		$identifier = $data_array[0];

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);
		$this->assertSame($identifier->get('type'), 'comments');
		$this->assertSame($identifier->get('id'), '5');

		$identifier = $data_array[1];

		$this->assertInstanceOf('Art4\JsonApiClient\Resource\Identifier', $identifier);
		$this->assertSame($identifier->get('type'), 'comments');
		$this->assertSame($identifier->get('id'), '12');

		$links = $resource->get('links');

		$this->assertInstanceOf('Art4\JsonApiClient\Link', $links);
		$this->assertTrue($links->has('self'));
		$this->assertSame($links->get('self'), 'http://example.com/articles/1');

		$includes = $document->get('included');

		$this->assertTrue(is_array($includes));
		$this->assertTrue(count($includes) === 3);

		$include = $includes[0];

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

		$include = $includes[1];

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

		$include = $includes[2];

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
	}
}
