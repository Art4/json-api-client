<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Functional;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\Factory;
use PHPUnit\Framework\TestCase;

class DotNotationTest extends TestCase
{
    use HelperTrait;

    /**
     * @test
     */
    public function testCompleteResourceObjectWithMultipleRelationships()
    {
        $manager = new ErrorAbortManager(new Factory());

        $string = $this->getJsonString('04_complete_document_with_multiple_relationships.json');

        $input = new ResponseStringInput($string);

        $document = $manager->parse($input);

        $this->assertTrue($document->has('data'));
        $this->assertInstanceOf(Accessable::class, $document->get('data'));

        $this->assertTrue($document->has('data.0'));
        $this->assertTrue($document->get('data')->has('0'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0'));
        $this->assertFalse($document->has('data.1'));

        $this->assertTrue($document->has('data.0.type'));
        $this->assertSame('articles', $document->get('data.0.type'));

        $this->assertTrue($document->has('data.0.id'));
        $this->assertSame('1', $document->get('data.0.id'));

        $this->assertTrue($document->has('data.0.attributes'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.attributes'));

        $this->assertTrue($document->has('data.0.attributes.title'));
        $this->assertSame('JSON API paints my bikeshed!', $document->get('data.0.attributes.title'));

        $this->assertTrue($document->has('data.0.links'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.links'));

        $this->assertTrue($document->has('data.0.links.self'));
        $this->assertSame('http://example.com/articles/1', $document->get('data.0.links.self'));

        $this->assertTrue($document->has('data.0.relationships'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships'));

        $this->assertTrue($document->has('data.0.relationships.author'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.author'));

        $this->assertTrue($document->has('data.0.relationships.author.links'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.author.links'));

        $this->assertTrue($document->has('data.0.relationships.author.links.self'));
        $this->assertSame('http://example.com/articles/1/relationships/author', $document->get('data.0.relationships.author.links.self'));

        $this->assertTrue($document->has('data.0.relationships.author.links.related'));
        $this->assertSame('http://example.com/articles/1/author', $document->get('data.0.relationships.author.links.related'));

        $this->assertTrue($document->has('data.0.relationships.author.data'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.author.data'));

        $this->assertTrue($document->has('data.0.relationships.author.data.type'));
        $this->assertSame('people', $document->get('data.0.relationships.author.data.type'));

        $this->assertTrue($document->has('data.0.relationships.author.data.id'));
        $this->assertSame('9', $document->get('data.0.relationships.author.data.id'));

        $this->assertTrue($document->has('data.0.relationships.comments'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.comments'));

        $this->assertTrue($document->has('data.0.relationships.comments.links'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.comments.links'));

        $this->assertTrue($document->has('data.0.relationships.comments.links.self'));
        $this->assertSame('http://example.com/articles/1/relationships/comments', $document->get('data.0.relationships.comments.links.self'));

        $this->assertTrue($document->has('data.0.relationships.comments.links.related'));
        $this->assertSame('http://example.com/articles/1/comments', $document->get('data.0.relationships.comments.links.related'));

        $this->assertTrue($document->has('data.0.relationships.comments.data'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.comments.data'));

        $this->assertTrue($document->has('data.0.relationships.comments.data.0'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.comments.data.0'));

        $this->assertTrue($document->has('data.0.relationships.comments.data.0.type'));
        $this->assertSame('comments', $document->get('data.0.relationships.comments.data.0.type'));

        $this->assertTrue($document->has('data.0.relationships.comments.data.0.id'));
        $this->assertSame('5', $document->get('data.0.relationships.comments.data.0.id'));

        $this->assertTrue($document->has('data.0.relationships.comments.data.1'));
        $this->assertInstanceOf(Accessable::class, $document->get('data.0.relationships.comments.data.1'));

        $this->assertTrue($document->has('data.0.relationships.comments.data.1.type'));
        $this->assertSame('comments', $document->get('data.0.relationships.comments.data.1.type'));

        $this->assertTrue($document->has('data.0.relationships.comments.data.1.id'));
        $this->assertSame('12', $document->get('data.0.relationships.comments.data.1.id'));

        $this->assertTrue($document->has('included'));
        $this->assertInstanceOf(Accessable::class, $document->get('included'));

        $this->assertTrue($document->has('included.0'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.0'));

        $this->assertTrue($document->has('included.0.type'));
        $this->assertSame('people', $document->get('included.0.type'));

        $this->assertTrue($document->has('included.0.id'));
        $this->assertSame('9', $document->get('included.0.id'));

        $this->assertTrue($document->has('included.0.attributes'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.0.attributes'));

        $this->assertTrue($document->has('included.0.attributes.first-name'));
        $this->assertSame('Dan', $document->get('included.0.attributes.first-name'));

        $this->assertTrue($document->has('included.0.attributes.last-name'));
        $this->assertSame('Gebhardt', $document->get('included.0.attributes.last-name'));

        $this->assertTrue($document->has('included.0.attributes.twitter'));
        $this->assertSame('dgeb', $document->get('included.0.attributes.twitter'));

        $this->assertTrue($document->has('included.0.links'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.0.links'));

        $this->assertTrue($document->has('included.0.links.self'));
        $this->assertSame('http://example.com/people/9', $document->get('included.0.links.self'));

        $this->assertTrue($document->has('included.1'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.1'));

        $this->assertTrue($document->has('included.1.type'));
        $this->assertSame('comments', $document->get('included.1.type'));

        $this->assertTrue($document->has('included.1.id'));
        $this->assertSame('5', $document->get('included.1.id'));

        $this->assertTrue($document->has('included.1.attributes'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.1.attributes'));

        $this->assertTrue($document->has('included.1.attributes.body'));
        $this->assertSame('First!', $document->get('included.1.attributes.body'));

        $this->assertTrue($document->has('included.1.links'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.1.links'));

        $this->assertTrue($document->has('included.1.links.self'));
        $this->assertSame('http://example.com/comments/5', $document->get('included.1.links.self'));

        $this->assertTrue($document->has('included.2'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.2'));

        $this->assertTrue($document->has('included.2.type'));
        $this->assertSame('comments', $document->get('included.2.type'));

        $this->assertTrue($document->has('included.2.id'));
        $this->assertSame('12', $document->get('included.2.id'));

        $this->assertTrue($document->has('included.2.attributes'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.2.attributes'));

        $this->assertTrue($document->has('included.2.attributes.body'));
        $this->assertSame('I like XML better', $document->get('included.2.attributes.body'));

        $this->assertTrue($document->has('included.2.links'));
        $this->assertInstanceOf(Accessable::class, $document->get('included.2.links'));

        $this->assertTrue($document->has('included.2.links.self'));
        $this->assertSame('http://example.com/comments/12', $document->get('included.2.links.self'));
    }

    /**
     * @test
     */
    public function testGetNotExistentValueThrowsException()
    {
        $manager = new ErrorAbortManager(new Factory());

        $string = $this->getJsonString('05_simple_meta_object.json');

        $input = new ResponseStringInput($string);

        $document = $manager->parse($input);

        // Test 3 segments, segment 2 don't exists
        $this->assertFalse($document->has('meta.foobar.zap'));

        // Test 3 segments, segment 3 don't exists
        $this->assertFalse($document->has('meta.random_object.zap'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"meta.random_object.zap" doesn\'t exist in Document.'
        );

        $document->get('meta.random_object.zap');
    }
}
