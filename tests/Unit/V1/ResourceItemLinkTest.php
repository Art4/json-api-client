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
use Art4\JsonApiClient\V1\ResourceItemLink;
use PHPUnit\Framework\TestCase;

class ResourceItemLinkTest extends TestCase
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
     * @test parsing of all properties
     */
    public function testParsingPropertiesExists(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';
        $object->custom = 'http://example.org/custom';
        $object->related = new \stdClass();

        $link = new ResourceItemLink($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ResourceItemLink::class, $link);
        $this->assertInstanceOf(Accessable::class, $link);
        $this->assertSame($link->getKeys(), ['self', 'custom', 'related']);

        $this->assertTrue($link->has('self'));
        $this->assertSame($link->get('self'), 'http://example.org/self');
        $this->assertTrue($link->has('custom'));
        $this->assertSame($link->get('custom'), 'http://example.org/custom');
        $this->assertTrue($link->has('related'));
        $this->assertInstanceOf(Accessable::class, $link->get('related'));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * links: a links object related to the primary data.
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectThrowsException($input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'ItemLink has to be an object, "' . gettype($input) . '" given.'
        );

        $link = new ResourceItemLink($input, $this->manager, $this->parent);
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * test create without object or string attribute throws exception
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectOrStringAttributeThrowsException($input): void
    {
        $object = new \stdClass();
        $object->input = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new ResourceItemLink($object, $this->manager, $this->parent);
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException(): void
    {
        $object = new \stdClass();
        $object->self = 'http://example.org/self';

        $link = new ResourceItemLink($object, $this->manager, $this->parent);

        $this->assertFalse($link->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
