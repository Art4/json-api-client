<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2018  Artur Weigandt  https://wlabs.de/kontakt

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
use Art4\JsonApiClient\V1\Link;

class LinkTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->setUpManagerMock();

        // Mock parent link
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @test create with object
     */
    public function testCreateWithObject()
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
     *
     * @param mixed $input
     */
    public function testHrefHasToBeAString($input)
    {
        $object = new \stdClass();
        $object->href = $input;

        if (gettype($input) === 'string') {
            $link = new Link($object, $this->manager, $this->parent);

            $this->assertTrue($link->has('href'));
            $this->assertTrue(is_string($link->get('href')));

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
    public function testHrefAttributeMustBeSet()
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
    public function testMetaIsParsedAsObject()
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
     *
     * @param mixed $input
     */
    public function testCreateWithDataprovider($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Link has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link = new Link($input, $this->manager, $this->parent);
    }
}
