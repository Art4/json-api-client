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
use Art4\JsonApiClient\V1\ErrorLink;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ErrorLinkTest extends TestCase
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
     * @test all properties can exist
     *
     * An error object MAY have the following members:
     * - links: a links object containing the following members:
     *   - about: a link that leads to further details about this particular occurrence of the problem.
     */
    public function testAllPropertiesExists(): void
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();
        $object->href = 'http://example.org/href';
        $object->about = 'http://example.org/about';

        $link = new ErrorLink($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ErrorLink::class, $link);
        $this->assertInstanceOf(Accessable::class, $link);
        $this->assertSame($link->getKeys(), ['about', 'meta', 'href']);

        $this->assertTrue($link->has('href'));
        $this->assertSame($link->get('href'), 'http://example.org/href');
        $this->assertTrue($link->has('meta'));
        $this->assertInstanceOf(Accessable::class, $link->get('meta'));
        $this->assertTrue($link->has('about'));
        $this->assertSame($link->get('about'), 'http://example.org/about');
    }

    /**
     * @test 'about' property must be set
     *
     * An error object MAY have the following members:
     * - links: a links object containing the following members:
     *   - about: a link that leads to further details about this particular occurrence of the problem.
     */
    public function testAboutMustBeSet(): void
    {
        $object = new \stdClass();
        $object->foobar = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'ErrorLink MUST contain these properties: about'
        );

        $link = new ErrorLink($object, $this->manager, $this->parent);
    }

    /**
     * @test 'about' property can be a link object
     *
     * An error object MAY have the following members:
     * - links: a links object containing the following members:
     *   - about: a link that leads to further details about this particular occurrence of the problem.
     */
    public function testAboutCanBeAnObject(): void
    {
        $object = new \stdClass();
        $object->about = new \stdClass();

        $link = new ErrorLink($object, $this->manager, $this->parent);

        $this->assertInstanceOf(ErrorLink::class, $link);
        $this->assertSame($link->getKeys(), ['about']);

        $this->assertTrue($link->has('about'));
        $this->assertInstanceOf(Accessable::class, $link->get('about'));
    }

    /**
     * The value of each links member MUST be an object (a "links object").
     */
    #[DataProvider('jsonValuesProviderWithoutObject')]
    public function testCreateWithDataprovider(mixed $input): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link has to be an object, "' . gettype($input) . '" given.'
        );

        $link = new ErrorLink($input, $this->manager, $this->parent);
    }

    /**
     * test create without object or string attribute throws exception
     */
    #[DataProvider('jsonValuesProviderWithoutObjectAndString')]
    public function testCreateWithoutObjectOrStringAttributeThrowsException(mixed $input): void
    {
        $object = new \stdClass();
        $object->about = 'http://example.org/about';
        $object->input = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new ErrorLink($object, $this->manager, $this->parent);
    }

    /**
     * The value of the about member MUST be an object (a "links object") or a string.
     */
    #[DataProvider('jsonValuesProviderWithoutObjectAndString')]
    public function testAboutWithDataproviderThrowsException(mixed $input): void
    {
        $object = new \stdClass();
        $object->about = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new ErrorLink($object, $this->manager, $this->parent);
    }

    public function testGetOnANonExistingKeyThrowsException(): void
    {
        $object = new \stdClass();
        $object->about = 'http://example.org/about';

        $link = new ErrorLink($object, $this->manager, $this->parent);

        $this->assertFalse($link->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
