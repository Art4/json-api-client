<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Functional;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Helper\Parser;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\Attributes;
use Art4\JsonApiClient\V1\Document;
use Art4\JsonApiClient\V1\DocumentLink;
use Art4\JsonApiClient\V1\Factory;
use Art4\JsonApiClient\V1\Jsonapi;
use Art4\JsonApiClient\V1\Link;
use Art4\JsonApiClient\V1\Meta;
use Art4\JsonApiClient\V1\Relationship;
use Art4\JsonApiClient\V1\RelationshipCollection;
use Art4\JsonApiClient\V1\RelationshipLink;
use Art4\JsonApiClient\V1\ResourceCollection;
use Art4\JsonApiClient\V1\ResourceIdentifier;
use Art4\JsonApiClient\V1\ResourceIdentifierCollection;
use Art4\JsonApiClient\V1\ResourceItem;
use Art4\JsonApiClient\V1\ResourceItemLink;
use Art4\JsonApiClient\V1\ResourceNull;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ParsingTest extends TestCase
{
    use HelperTrait;

    /**
     * Provide Parser
     *
     * @return array<array<\Closure>>
     */
    public static function createParserProvider(): array
    {
        $errorAbortManagerParser = function ($string) {
            $manager = new ErrorAbortManager(new Factory());

            return $manager->parse(new ResponseStringInput($string));
        };

        return [
            [$errorAbortManagerParser],
        ];
    }

    #[DataProvider('createParserProvider')]
    public function testParseSimpleResourceWithDifferentParser(callable $parser): void
    {
        $string = $this->getJsonString('01_simple_resource.json');
        $document = $parser($string);

        $this->assertInstanceOf(Accessable::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('relationships'));
        $this->assertFalse($document->has('included'));
        $this->assertTrue($document->has('data'));

        $resource = $document->get('data');

        $this->assertInstanceOf(ResourceItem::class, $resource);
        $this->assertFalse($resource->has('meta'));
        $this->assertSame($resource->get('type'), 'articles');
        $this->assertTrue($resource->has('id'));
        $this->assertSame($resource->get('id'), '1');
        $this->assertTrue($resource->has('attributes'));

        $attributes = $resource->get('attributes');

        $this->assertInstanceOf(Attributes::class, $attributes);
        $this->assertTrue($attributes->has('title'));
        $this->assertSame($attributes->get('title'), 'JSON API paints my bikeshed!');
    }

    public function testParseSimpleResourceIdentifier(): void
    {
        $string = $this->getJsonString('02_simple_resource_identifier.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('included'));
        $this->assertTrue($document->has('data'));

        $resource = $document->get('data');

        $this->assertInstanceOf(ResourceIdentifier::class, $resource);
        $this->assertFalse($resource->has('meta'));
        $this->assertSame($resource->get('type'), 'articles');
        $this->assertSame($resource->get('id'), '1');
    }

    public function testParseResourceObject(): void
    {
        $string = $this->getJsonString('03_resource_object.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('included'));
        $this->assertTrue($document->has('data'));

        $resource = $document->get('data');

        $this->assertInstanceOf(ResourceItem::class, $resource);
        $this->assertTrue($resource->has('meta'));
        $this->assertSame($resource->get('type'), 'articles');
        $this->assertSame($resource->get('id'), '1');
        $this->assertTrue($resource->has('attributes'));
        $this->assertTrue($resource->has('relationships'));

        $meta = $resource->get('meta');

        $this->assertInstanceOf(Meta::class, $meta);
        $this->assertTrue($meta->has('foo'));
        $this->assertSame($meta->get('foo'), 'bar');

        $attributes = $resource->get('attributes');

        $this->assertInstanceOf(Attributes::class, $attributes);
        $this->assertTrue($attributes->has('title'));
        $this->assertSame($attributes->get('title'), 'Rails is Omakase');

        $collection = $resource->get('relationships');

        $this->assertInstanceOf(RelationshipCollection::class, $collection);
        $this->assertTrue($collection->has('author'));

        $author = $collection->get('author');

        $this->assertInstanceOf(Relationship::class, $author);
        $this->assertTrue($author->has('links'));
        $this->assertTrue($author->has('data'));

        $links = $author->get('links');

        $this->assertInstanceOf(RelationshipLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), '/articles/1/relationships/author');
        $this->assertTrue($links->has('related'));
        $this->assertSame($links->get('related'), '/articles/1/author');

        $data = $author->get('data');

        $this->assertInstanceOf(ResourceIdentifier::class, $data);
        $this->assertSame($data->get('type'), 'people');
        $this->assertSame($data->get('id'), '9');
        $data = $author->get('data');

        $this->assertInstanceOf(ResourceIdentifier::class, $data);
        $this->assertSame($data->get('type'), 'people');
        $this->assertSame($data->get('id'), '9');
    }

    public function testParseCompleteResourceObjectWithMultipleRelationships(): void
    {
        $string = $this->getJsonString('04_complete_document_with_multiple_relationships.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertTrue($document->has('included'));
        $this->assertTrue($document->has('data'));

        /** @var Accessable */
        $resources = $document->get('data');

        $this->assertInstanceOf(ResourceCollection::class, $resources);

        $this->assertTrue(count($resources->getKeys()) === 1);
        $this->assertSame($resources->getKeys(), [0]);

        $this->assertTrue($resources->has(0));

        /** @var Accessable */
        $resource = $resources->get(0);

        $this->assertFalse($resource->has('meta'));
        $this->assertSame($resource->get('type'), 'articles');
        $this->assertSame($resource->get('id'), '1');
        $this->assertTrue($resource->has('attributes'));
        $this->assertTrue($resource->has('relationships'));
        $this->assertTrue($resource->has('links'));

        /** @var Accessable */
        $attributes = $resource->get('attributes');

        $this->assertInstanceOf(Attributes::class, $attributes);
        $this->assertTrue($attributes->has('title'));
        $this->assertSame($attributes->get('title'), 'JSON API paints my bikeshed!');

        /** @var Accessable */
        $collection = $resource->get('relationships');

        $this->assertInstanceOf(RelationshipCollection::class, $collection);
        $this->assertTrue($collection->has('author'));
        $this->assertTrue($collection->has('comments'));

        /** @var Accessable */
        $author = $collection->get('author');

        $this->assertInstanceOf(Relationship::class, $author);
        $this->assertTrue($author->has('links'));
        $this->assertTrue($author->has('data'));

        /** @var Accessable */
        $links = $author->get('links');

        $this->assertInstanceOf(RelationshipLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), 'http://example.com/articles/1/relationships/author');
        $this->assertTrue($links->has('related'));
        $this->assertSame($links->get('related'), 'http://example.com/articles/1/author');

        /** @var Accessable */
        $data = $author->get('data');

        $this->assertInstanceOf(ResourceIdentifier::class, $data);
        $this->assertSame($data->get('type'), 'people');
        $this->assertSame($data->get('id'), '9');

        /** @var Accessable */
        $comments = $collection->get('comments');

        $this->assertInstanceOf(Relationship::class, $comments);
        $this->assertTrue($comments->has('links'));
        $this->assertTrue($comments->has('data'));

        /** @var Accessable */
        $links = $comments->get('links');

        $this->assertInstanceOf(RelationshipLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), 'http://example.com/articles/1/relationships/comments');
        $this->assertTrue($links->has('related'));
        $this->assertSame($links->get('related'), 'http://example.com/articles/1/comments');

        /** @var Accessable */
        $data_array = $comments->get('data');

        $this->assertInstanceOf(ResourceIdentifierCollection::class, $data_array);
        $this->assertCount(2, $data_array->getKeys());

        /** @var Accessable */
        $identifier = $data_array->get(0);

        $this->assertInstanceOf(ResourceIdentifier::class, $identifier);
        $this->assertSame($identifier->get('type'), 'comments');
        $this->assertSame($identifier->get('id'), '5');

        /** @var Accessable */
        $identifier = $data_array->get(1);

        $this->assertInstanceOf(ResourceIdentifier::class, $identifier);
        $this->assertSame($identifier->get('type'), 'comments');
        $this->assertSame($identifier->get('id'), '12');

        /** @var Accessable */
        $links = $resource->get('links');

        $this->assertInstanceOf(ResourceItemLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), 'http://example.com/articles/1');

        /** @var Accessable */
        $includes = $document->get('included');

        $this->assertInstanceOf(ResourceCollection::class, $includes);
        $this->assertSame($includes->getKeys(), [0, 1, 2]);

        $this->assertTrue($includes->has(0));

        /** @var Accessable */
        $include = $includes->get(0);

        $this->assertInstanceOf(ResourceItem::class, $include);
        $this->assertSame($include->get('type'), 'people');
        $this->assertSame($include->get('id'), '9');
        $this->assertTrue($include->has('attributes'));
        $this->assertTrue($include->has('links'));

        /** @var Accessable */
        $attributes = $include->get('attributes');

        $this->assertInstanceOf(Attributes::class, $attributes);
        $this->assertTrue($attributes->has('first-name'));
        $this->assertSame($attributes->get('first-name'), 'Dan');
        $this->assertTrue($attributes->has('last-name'));
        $this->assertSame($attributes->get('last-name'), 'Gebhardt');
        $this->assertTrue($attributes->has('twitter'));
        $this->assertSame($attributes->get('twitter'), 'dgeb');

        /** @var Accessable */
        $links = $include->get('links');

        $this->assertInstanceOf(ResourceItemLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), 'http://example.com/people/9');

        $this->assertTrue($includes->has(1));

        /** @var Accessable */
        $include = $includes->get(1);

        $this->assertInstanceOf(ResourceItem::class, $include);
        $this->assertSame($include->get('type'), 'comments');
        $this->assertSame($include->get('id'), '5');
        $this->assertTrue($include->has('attributes'));
        $this->assertTrue($include->has('links'));

        $attributes = $include->get('attributes');

        $this->assertInstanceOf(Attributes::class, $attributes);
        $this->assertTrue($attributes->has('body'));
        $this->assertSame($attributes->get('body'), 'First!');

        $links = $include->get('links');

        $this->assertInstanceOf(ResourceItemLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), 'http://example.com/comments/5');

        $this->assertTrue($includes->has(2));
        $include = $includes->get(2);

        $this->assertInstanceOf(ResourceItem::class, $include);
        $this->assertSame($include->get('type'), 'comments');
        $this->assertSame($include->get('id'), '12');
        $this->assertTrue($include->has('attributes'));
        $this->assertTrue($include->has('links'));

        $attributes = $include->get('attributes');

        $this->assertInstanceOf(Attributes::class, $attributes);
        $this->assertTrue($attributes->has('body'));
        $this->assertSame($attributes->get('body'), 'I like XML better');

        $links = $include->get('links');

        $this->assertInstanceOf(ResourceItemLink::class, $links);
        $this->assertTrue($links->has('self'));
        $this->assertSame($links->get('self'), 'http://example.com/comments/12');
    }

    public function testParsePaginationExample(): void
    {
        $string = $this->getJsonString('06_pagination_example.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('data'));
        $this->assertTrue($document->has('links'));
        $this->assertTrue($document->has('meta'));

        $resource_collection = $document->get('data');

        $this->assertInstanceOf(ResourceCollection::class, $resource_collection);
        $this->assertTrue($resource_collection->has(0));
        $this->assertFalse($resource_collection->has(1));

        $resource = $resource_collection->get(0);

        $this->assertInstanceOf(ResourceItem::class, $resource);
        $this->assertFalse($resource->has('meta'));
        $this->assertSame($resource->get('type'), 'articles');
        $this->assertSame($resource->get('id'), '3');
        $this->assertTrue($resource->has('attributes'));
        $this->assertInstanceOf(Attributes::class, $resource->get('attributes'));

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

        $this->assertInstanceOf(DocumentLink::class, $links);
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
    }

    public function testParseRelationshipExample(): void
    {
        $string = $this->getJsonString('07_relationship_example_without_data.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('data'));

        $this->assertInstanceOf(ResourceCollection::class, $document->get('data'));
        $this->assertTrue($document->has('data.0'));
        $this->assertFalse($document->has('data.1'));

        $this->assertInstanceOf(ResourceItem::class, $document->get('data.0'));
        $this->assertFalse($document->has('data.0.meta'));
        $this->assertSame($document->get('data.0.type'), 'articles');
        $this->assertSame($document->get('data.0.id'), '1');
        $this->assertTrue($document->has('data.0.attributes'));
        $this->assertInstanceOf(Attributes::class, $document->get('data.0.attributes'));

        $this->assertTrue($document->has('data.0.attributes.title'));
        $this->assertSame($document->get('data.0.attributes.title'), 'JSON API paints my bikeshed!');

        $this->assertTrue($document->has('data.0.relationships'));
        $this->assertInstanceOf(RelationshipCollection::class, $document->get('data.0.relationships'));

        $this->assertTrue($document->has('data.0.relationships.comments'));
        $this->assertInstanceOf(Relationship::class, $document->get('data.0.relationships.comments'));
        $this->assertCount(2, $document->get('data.0.relationships.comments')->getKeys());

        $this->assertTrue($document->has('data.0.relationships.comments.meta'));
        $this->assertInstanceOf(Meta::class, $document->get('data.0.relationships.comments.meta'));
        $this->assertTrue($document->has('data.0.relationships.comments.meta.foo'));
        $this->assertSame($document->get('data.0.relationships.comments.meta.foo'), 'bar');

        $this->assertTrue($document->has('data.0.relationships.comments.links'));
        $this->assertInstanceOf(RelationshipLink::class, $document->get('data.0.relationships.comments.links'));

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
        $this->assertInstanceOf(Link::class, $document->get('data.0.relationships.comments.links.related'));

        $this->assertTrue($document->has('data.0.relationships.comments.links.related.href'));
        $this->assertSame($document->get('data.0.relationships.comments.links.related.href'), 'http://example.com/articles/1/comments');

        $this->assertTrue($document->has('data.0.relationships.comments.links.related.meta'));
        $this->assertInstanceOf(Meta::class, $document->get('data.0.relationships.comments.links.related.meta'));

        $this->assertTrue($document->has('data.0.relationships.comments.links.related.meta.count'));
        $this->assertSame($document->get('data.0.relationships.comments.links.related.meta.count'), 10);
    }

    public function testParseObjectLinksExample(): void
    {
        $string = $this->getJsonString('08_object_links.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('links'));

        $this->assertInstanceOf(DocumentLink::class, $document->get('links'));
        $this->assertTrue($document->has('links.self'));
        $this->assertTrue($document->has('links.first'));
        $this->assertTrue($document->has('links.next'));
        $this->assertTrue($document->has('links.last'));

        $this->assertInstanceOf(Link::class, $document->get('links.self'));
        $this->assertTrue($document->has('links.self.href'));
        $this->assertSame('?page[number]=1&page[size]=10', $document->get('links.self.href'));

        $this->assertInstanceOf(Link::class, $document->get('links.first'));
        $this->assertTrue($document->has('links.first.href'));
        $this->assertSame('?page[number]=1&page[size]=10', $document->get('links.first.href'));

        $this->assertInstanceOf(Link::class, $document->get('links.next'));
        $this->assertTrue($document->has('links.next.href'));
        $this->assertSame('?page[number]=2&page[size]=10', $document->get('links.next.href'));

        $this->assertInstanceOf(Link::class, $document->get('links.last'));
        $this->assertTrue($document->has('links.last.href'));
        $this->assertSame('?page[number]=11&page[size]=10', $document->get('links.last.href'));

        $this->assertTrue($document->has('jsonapi'));
        $this->assertInstanceOf(Jsonapi::class, $document->get('jsonapi'));
        $this->assertTrue($document->has('jsonapi.version'));
        $this->assertSame('1.0', $document->get('jsonapi.version'));
        $this->assertTrue($document->has('jsonapi.meta'));
        $this->assertInstanceOf(Meta::class, $document->get('jsonapi.meta'));
        $this->assertTrue($document->has('jsonapi.meta.foo'));
        $this->assertSame('bar', $document->get('jsonapi.meta.foo'));
    }

    public function testParseResourceIdentifierWithMeta(): void
    {
        $string = $this->getJsonString('11_resource_identifier_with_meta.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('included'));
        $this->assertTrue($document->has('data'));

        $resource = $document->get('data');

        $this->assertInstanceOf(ResourceIdentifier::class, $resource);
        $this->assertTrue($resource->has('meta'));
        $this->assertTrue($resource->has('meta.foo'));
        $this->assertSame($resource->get('meta.foo'), 'bar');
        $this->assertSame($resource->get('type'), 'articles');
        $this->assertSame($resource->get('id'), '2');
    }

    public function testParseNullResource(): void
    {
        $string = $this->getJsonString('12_null_resource.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('included'));
        $this->assertTrue($document->has('data'));

        $resource = $document->get('data');

        $this->assertInstanceOf(ResourceNull::class, $resource);
    }

    public function testParseResourceIdentifierCollectionWithMeta(): void
    {
        $string = $this->getJsonString('13_collection_with_resource_identifier_with_meta.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('meta'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('included'));
        $this->assertTrue($document->has('data'));

        $collection = $document->get('data');
        $this->assertInstanceOf(ResourceCollection::class, $collection);

        $this->assertTrue($collection->has('0'));
        $resource0 = $collection->get('0');

        $this->assertInstanceOf(ResourceIdentifier::class, $resource0);
        $this->assertSame($resource0->get('type'), 'articles');
        $this->assertSame($resource0->get('id'), '1');

        $this->assertTrue($collection->has('1'));
        $resource1 = $collection->get('1');

        $this->assertInstanceOf(ResourceIdentifier::class, $resource1);
        $this->assertTrue($resource1->has('meta'));
        $this->assertTrue($resource1->has('meta.foo'));
        $this->assertSame($resource1->get('meta.foo'), 'bar');
        $this->assertSame($resource1->get('type'), 'articles');
        $this->assertSame($resource1->get('id'), '2');
    }

    public function testParseCreateResourceWithoutId(): void
    {
        $string = $this->getJsonString('14_create_resource_without_id.json');
        $document = Parser::parseRequestString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame(['data'], $document->getKeys());
    }

    public function testParseCreateShortResourceWithoutId(): void
    {
        $string = $this->getJsonString('15_create_resource_without_id.json');
        $document = Parser::parseRequestString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame(['data'], $document->getKeys());
    }

    public function testExceptionIfIdIsNotString(): void
    {
        $this->expectException(\Art4\JsonApiClient\Exception\ValidationException::class);
        $this->expectExceptionMessage('A resource id MUST be a string');

        $string = $this->getJsonString('invalid/resource_id_must_be_a_string.json');
        $document = Parser::parseResponseString($string);
    }

    public function testParseLinksInRelationshipsCorrectly(): void
    {
        $string = $this->getJsonString('17_relationship_links.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame(['data'], $document->getKeys());
    }

    public function testParseNormativeStatementsForVersion10Correctly(): void
    {
        $string = $this->getJsonString('format_1.0/normative-statements.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame(['jsonapi', 'data', 'included'], $document->getKeys());
        $this->assertSame('1.0', $document->get('jsonapi.version'));
        $this->assertInstanceOf(Accessable::class, $document->get('data'));
        $this->assertCount(6, $document->get('data')->getKeys());
        $this->assertInstanceOf(Accessable::class, $document->get('included'));
        $this->assertCount(184, $document->get('included')->getKeys());
    }

    public function testParseNormativeStatementsForVersion11Correctly(): void
    {
        $string = $this->getJsonString('format_1.1/normative-statements.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame(['jsonapi', 'data', 'included'], $document->getKeys());
        $this->assertSame('1.1', $document->get('jsonapi.version'));
        $this->assertInstanceOf(Accessable::class, $document->get('data'));
        $this->assertCount(6, $document->get('data')->getKeys());
        $this->assertInstanceOf(Accessable::class, $document->get('included'));
        $this->assertCount(188, $document->get('included')->getKeys());
    }

    public function testParseJsonApiObjectWithVersion11Correctly(): void
    {
        $string = $this->getJsonString('18_jsonapi_object_with_ext_profile.json');
        $document = Parser::parseResponseString($string);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame(['meta', 'jsonapi'], $document->getKeys());
        $this->assertInstanceOf(Accessable::class, $document->get('jsonapi'));
        $this->assertSame(['version', 'ext', 'profile'], $document->get('jsonapi')->getKeys());
        $this->assertSame('1.1', $document->get('jsonapi.version'));
    }
}
