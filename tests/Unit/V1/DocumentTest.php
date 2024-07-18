<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\Document;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DocumentTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp(): void
    {
        $this->setUpManagerMock();

        // Mock parent
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @test create with object returns self
     */
    public function testCreateWithObjectReturnsSelf(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();
        $object->fc = 'test property for forward compatability';

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertInstanceOf(Accessable::class, $document);
        $this->assertSame($document->getKeys(), ['meta', 'fc']);
        $this->assertTrue($document->has('meta'));
        $this->assertTrue($document->has('fc'));
        $this->assertInstanceOf(Accessable::class, $document->get('meta'));
        $this->assertFalse($document->has('data'));
        $this->assertFalse($document->has('errors'));
        $this->assertFalse($document->has('jsonapi'));
        $this->assertFalse($document->has('links'));
        $this->assertFalse($document->has('included'));
    }

    /**
     * @test create with all possible values
     */
    public function testCreateWithAllPossibleValues(): void
    {
        $object = new \stdClass();
        $object->data = new \stdClass();
        $object->data->type = 'types';
        $object->data->id = 'id';
        $object->meta = new \stdClass();
        $object->included = [];
        $object->included[0] = new \stdClass();
        $object->included[0]->type = 'types';
        $object->included[0]->id = 'id';
        $object->jsonapi = new \stdClass();
        $object->links = new \stdClass();
        $object->fc = 'test property for forward compatability';

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame($document->getKeys(), ['data', 'meta', 'included', 'jsonapi', 'links', 'fc']);
        $this->assertTrue($document->has('data'));
        $this->assertTrue($document->has('meta'));
        $this->assertFalse($document->has('errors'));
        $this->assertTrue($document->has('jsonapi'));
        $this->assertTrue($document->has('links'));
        $this->assertTrue($document->has('included'));
        $this->assertTrue($document->has('fc'));
    }

    /**
     * A JSON object MUST be at the root of every JSON API request and response containing data.
     */
    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithDataproviderThrowsException(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            sprintf('Document has to be an object, "%s" given.', gettype($input))
        );

        $document = new Document($input, $this->manager, $this->parent);
    }

    /**
     * @test
     *
     * A document MUST contain at least one of the following top-level members: data, errors, meta
     */
    public function testCreateWithoutAnyToplevelMemberThrowsException(): void
    {
        $object = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Document MUST contain at least one of the following properties: data, errors, meta'
        );

        $document = new Document($object, $this->manager, $this->parent);
    }

    /**
     * @test create with data resource identifier
     *
     * A document MUST contain at least one of the following top-level members: data: the document's "primary data"
     * Primary data MUST be either:
     * - a single resource object, a single resource identifier object, or null, for requests that target single resources
     * - an array of resource objects, an array of resource identifier objects, or an empty array ([]), for requests that target resource collections
     */
    public function testCreateDataWithResourceIdentifier(): void
    {
        $data = new \stdClass();
        $data->type = 'posts';
        $data->id = 5;

        $object = new \stdClass();
        $object->data = $data;

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('data'));

        $this->assertInstanceOf(Accessable::class, $document->get('data'));
    }

    /**
     * @test create with data resource identifier and meta
     */
    public function testCreateDataWithResourceIdentifierAndMeta(): void
    {
        $data = new \stdClass();
        $data->type = 'posts';
        $data->id = 5;
        $data->meta = new \stdClass();

        $object = new \stdClass();
        $object->data = $data;

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);

        $this->assertTrue($document->has('data'));
        $this->assertInstanceOf(Accessable::class, $document->get('data'));
    }

    /**
     * @test create with data resource item
     */
    public function testCreateDataWithResourceItem(): void
    {
        $data = new \stdClass();
        $data->type = 'posts';
        $data->id = 5;
        $data->attributes = new \stdClass();
        $data->attributes->title = 'The post title';

        $object = new \stdClass();
        $object->data = $data;

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('data'));

        $this->assertInstanceOf(Accessable::class, $document->get('data'));
    }

    /**
     * @test create with data object array
     */
    public function testCreateDataWithResourceCollectionIdentifiers(): void
    {
        $data_obj = new \stdClass();
        $data_obj->type = 'types';
        $data_obj->id = 5;

        $object = new \stdClass();
        $object->data = [$data_obj];

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('data'));

        $this->assertInstanceOf(Accessable::class, $document->get('data'));
    }

    /**
     * @test create with data object array advanced
     */
    public function testCreateDataWithResourceCollectionResources(): void
    {
        $data_obj = new \stdClass();
        $data_obj->type = 'types';
        $data_obj->id = 5;
        $data_obj->attributes = new \stdClass();
        $data_obj->attributes->title = 'The title';

        $object = new \stdClass();
        $object->data = [$data_obj];

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('data'));

        $this->assertInstanceOf(Accessable::class, $document->get('data'));
    }

    /**
     * Test create with any value in data
     */
    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithDataproviderInValue(mixed $input): void
    {

        // Test with empty array in data
        if (gettype($input) === 'array') {
            $object = new \stdClass();
            $object->data = $input;

            $document = new Document($object, $this->manager, $this->parent);

            $this->assertInstanceOf(Document::class, $document);
            $this->assertTrue($document->has('data'));

            $collection = $document->get('data');

            $this->assertInstanceOf(Accessable::class, $collection);

            return;
        }

        // Test with null in data
        if (gettype($input) === 'NULL') {
            $object = new \stdClass();
            $object->data = $input;

            $document = new Document($object, $this->manager, $this->parent);

            $this->assertInstanceOf(Document::class, $document);
            $this->assertTrue($document->has('data'));

            $this->assertInstanceOf(Accessable::class, $document->get('data'));

            return;
        }

        $object = new \stdClass();
        $object->data = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Data value has to be null or an object, "' . gettype($input) . '" given.'
        );

        $document = new Document($object, $this->manager, $this->parent);
    }

    /**
     * @test create with an errors array
     */
    public function testCreateWithErrorsArray(): void
    {
        $object = new \stdClass();
        $object->errors = [
            new \stdClass(),
            new \stdClass(),
        ];

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertSame($document->getKeys(), ['errors']);
        $this->assertTrue($document->has('errors'));

        $this->assertInstanceOf(Accessable::class, $document->get('errors'));
    }

    /**
     * @test The members `data` and `errors` MUST NOT coexist in the same document.
     */
    public function testCreateWithDataAndErrorsThrowsException(): void
    {
        $object = new \stdClass();
        $object->data = new \stdClass();
        $object->errors = [];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'The properties `data` and `errors` MUST NOT coexist in Document.'
        );

        $document = new Document($object, $this->manager, $this->parent);
    }

    /**
     * @test create with meta object
     */
    public function testCreateWithMetaObject(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('meta'));

        $this->assertInstanceOf(Accessable::class, $document->get('meta'));
    }

    /**
     * @test create with Jsonapi object
     */
    public function testCreateWithJsonapiObject(): void
    {
        $object = new \stdClass();

        $object->meta = new \stdClass();
        $object->jsonapi = new \stdClass();

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('jsonapi'));

        $this->assertInstanceOf(Accessable::class, $document->get('jsonapi'));
    }

    /**
     * @test create with link object
     */
    public function testCreateWithLinkObject(): void
    {
        $object = new \stdClass();

        $object->meta = new \stdClass();
        $object->links = new \stdClass();

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('links'));

        $this->assertInstanceOf(Accessable::class, $document->get('links'));
    }

    /**
     * @test create with included objects
     */
    public function testCreateWithIncludedObjects(): void
    {
        $data = new \stdClass();
        $data->type = 'posts';
        $data->id = 5;

        $object = new \stdClass();
        $object->data = $data;
        $object->included = [
            $data,
            $data,
        ];

        $document = new Document($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertTrue($document->has('included'));

        $resources = $document->get('included');

        $this->assertInstanceOf(Accessable::class, $resources);
    }

    /**
     * @test If a document does not contain a top-level `data` key, the `included` member MUST NOT be present either.
     */
    public function testCreateIncludedWithoutDataThrowsException(): void
    {
        $object = new \stdClass();
        $object->included = new \stdClass();
        $object->meta = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'If Document does not contain a `data` property, the `included` property MUST NOT be present either.'
        );

        $document = new Document($object, $this->manager, $this->parent);
    }

    public function testGetOnANonExistingKeyThrowsException(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();

        $document = new Document($object, $this->manager, $this->parent);

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in Document.'
        );

        $document->get('something');
    }
}
