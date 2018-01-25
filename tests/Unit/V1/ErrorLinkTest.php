<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2017  Artur Weigandt  https://wlabs.de/kontakt

 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Art4\JsonApiClient\Tests\Unit\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\ErrorLink;

class ErrorLinkTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->setUpManagerMock();
    }

    /**
     * @test all properties can exist
     *
     * An error object MAY have the following members:
     * - links: a links object containing the following members:
     *   - about: a link that leads to further details about this particular occurrence of the problem.
     */
    public function testAllPropertiesExists()
    {
        $object = new \stdClass();
        $object->meta = new \stdClass();
        $object->href = 'http://example.org/href';
        $object->about = 'http://example.org/about';

        $link = new ErrorLink($object, $this->manager, $this->createMock(Accessable::class));

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
    public function testAboutMustBeSet()
    {
        $object = new \stdClass();
        $object->foobar = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'ErrorLink MUST contain these properties: about'
        );

        $link = new ErrorLink($object, $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @test 'about' property can be a link object
     *
     * An error object MAY have the following members:
     * - links: a links object containing the following members:
     *   - about: a link that leads to further details about this particular occurrence of the problem.
     */
    public function testAboutCanBeAnObject()
    {
        $object = new \stdClass();
        $object->about = new \stdClass();

        $link = new ErrorLink($object, $this->manager, $this->createMock(Accessable::class));

        $this->assertInstanceOf(ErrorLink::class, $link);
        $this->assertSame($link->getKeys(), ['about']);

        $this->assertTrue($link->has('about'));
        $this->assertInstanceOf(Accessable::class, $link->get('about'));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * The value of each links member MUST be an object (a "links object").
     *
     * @param mixed $input
     */
    public function testCreateWithDataprovider($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link has to be an object, "' . gettype($input) . '" given.'
        );

        $link = new ErrorLink($input, $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * test create without object or string attribute throws exception
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectOrStringAttributeThrowsException($input)
    {
        $object = new \stdClass();
        $object->about = 'http://example.org/about';
        $object->input = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new ErrorLink($object, $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObjectAndString
     *
     * The value of the about member MUST be an object (a "links object") or a string.
     *
     * @param mixed $input
     */
    public function testAboutWithDataproviderThrowsException($input)
    {
        $object = new \stdClass;
        $object->about = $input;

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new ErrorLink($object, $this->manager, $this->createMock(Accessable::class));
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException()
    {
        $object = new \stdClass();
        $object->about = 'http://example.org/about';

        $link = new ErrorLink($object, $this->manager, $this->createMock(Accessable::class));

        $this->assertFalse($link->has('something'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
