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
use Art4\JsonApiClient\V1\ResourceItemLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;

class ResourceItemLinkTest extends TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->setUpManagerMock();

        // Mock parent
        $this->parent = $this->createMock(Accessable::class);
    }

    /**
     * @test parsing of all properties
     */
    public function testParsingPropertiesExists()
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
    public function testCreateWithoutObjectThrowsException($input)
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
    public function testCreateWithoutObjectOrStringAttributeThrowsException($input)
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
    public function testGetOnANonExistingKeyThrowsException()
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
