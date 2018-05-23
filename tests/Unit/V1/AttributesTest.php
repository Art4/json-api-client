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
use Art4\JsonApiClient\V1\Attributes;

class AttributesTest extends TestCase
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
     * @test create with object
     */
    public function testCreateWithObject()
    {
        $object = new \stdClass();
        $object->object = new \stdClass();
        $object->array = [];
        $object->string = 'string';
        $object->number_int = 654;
        $object->number_float = 654.321;
        $object->true = true;
        $object->false = false;
        $object->null = null;

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );

        $this->assertInstanceOf(Accessable::class, $attributes);
        $this->assertTrue($attributes->has('object'));
        $this->assertTrue(is_object($attributes->get('object')));
        $this->assertTrue($attributes->has('array'));
        $this->assertTrue(is_array($attributes->get('array')));
        $this->assertTrue($attributes->has('string'));
        $this->assertTrue(is_string($attributes->get('string')));
        $this->assertTrue($attributes->has('number_int'));
        $this->assertTrue(is_int($attributes->get('number_int')));
        $this->assertTrue($attributes->has('number_float'));
        $this->assertTrue(is_float($attributes->get('number_float')));
        $this->assertTrue($attributes->has('true'));
        $this->assertTrue($attributes->get('true'));
        $this->assertTrue($attributes->has('false'));
        $this->assertFalse($attributes->get('false'));
        $this->assertTrue($attributes->has('null'));
        $this->assertNull($attributes->get('null'));
        $this->assertSame([
            'object',
            'array',
            'string',
            'number_int',
            'number_float',
            'true',
            'false',
            'null'
        ], $attributes->getKeys());
    }

    /**
     * @dataProvider jsonValuesProviderWithoutObject
     *
     * @param mixed $input
     */
    public function testCreateWithDataProvider($input)
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'Attributes has to be an object, "' . gettype($input) . '" given.'
        );

        $attributes = new Attributes(
            $input,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithTypePropertyThrowsException()
    {
        $object = new \stdClass();
        $object->type = 'posts';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithIdPropertyThrowsException()
    {
        $object = new \stdClass();
        $object->id = '5';

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithRelationshipsPropertyThrowsException()
    {
        $object = new \stdClass();
        $object->relationships = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     *
     * These properties are not allowed in attributes: `type`, `id`, `relationships`, `links`
     */
    public function testCreateWithLinksPropertyThrowsException()
    {
        $object = new \stdClass();
        $object->links = new \stdClass();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage(
            'These properties are not allowed in attributes: '.
            '`type`, `id`, `relationships`, `links`'
        );

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException()
    {
        $object = new \stdClass();
        $object->pages = '1126';

        $attributes = new Attributes(
            $object,
            $this->manager,
            $this->parent
        );

        $this->assertFalse($attributes->has('foobar'));

        $this->expectException(AccessException::class);
        $this->expectExceptionMessage(
            '"foobar" doesn\'t exist in this object.'
        );

        $attributes->get('foobar');
    }
}
