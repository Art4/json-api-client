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
use Art4\JsonApiClient\V1\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp(): void
    {
        $this->setUpManagerMock();

        // Mock parent link
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @test create with object
     */
    public function testCreateWithObject(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();
        $object->href = 'http://example.org/href';
        $object->link = 'http://example.org/link';

        $link = new Link($object, $this->manager, $this->parent);

        $this->assertInstanceOf(Accessable::class, $link);

        $this->assertTrue($link->has('href'));
        $this->assertSame('http://example.org/href', $link->get('href'));
        $this->assertTrue($link->has('meta'));
        $this->assertInstanceOf(Accessable::class, $link->get('meta'));
        $this->assertTrue($link->has('link'));
        $this->assertSame('http://example.org/link', $link->get('link'));

        // test get() with not existing key throws an exception
        $this->assertFalse($link->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * - an object ("link object") which can contain the following members:
     *   - href: a string containing the link's URL.
     */
    public function testHrefHasToBeAString(mixed $input): void
    {
        $object = new \stdClass();
        $object->href = $input;

        if (gettype($input) === 'string') {
            $link = new Link($object, $this->manager, $this->parent);

            $this->assertTrue($link->has('href'));
            $this->assertIsString($link->get('href'));

            return;
        }

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Every link attribute has to be a string, "' . gettype($input) . '" given.'
        );

        $link = new Link($object, $this->manager, $this->parent);
    }

    /**
     * @test href attribute must be set
     *
     * - an object ("link object") which can contain the following members:
     *   - href: a string containing the link's URL.
     */
    public function testHrefAttributeMustBeSet(): void
    {
        $object = new \stdClass();
        $object->related = 'http://example.org/related';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link must have a "href" attribute.'
        );

        $link = new Link($object, $this->manager, $this->parent);
    }

    /**
     * @test meta attribute will be parsed as Meta object inside Link
     */
    public function testMetaIsParsedAsObject(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();
        $object->href = 'http://example.org/href';

        $link = new Link($object, $this->manager, $this->parent);

        $this->assertTrue($link->has('meta'));
        $this->assertInstanceOf(Accessable::class, $link->get('meta'));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * The value of each links member MUST be an object (a "links object").
     */
    public function testCreateWithDataprovider(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new Link($input, $this->manager, $this->parent);
    }
}
