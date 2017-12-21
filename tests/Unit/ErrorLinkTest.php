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

namespace Art4\JsonApiClient\Tests\Unit;

use Art4\JsonApiClient\ErrorLink;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;

class ErrorLinkTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
    use HelperTrait;

    /**
     * @setup
     */
    public function setUp()
    {
        $this->manager = $this->buildManagerMock();
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

        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $link->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);
        $this->assertInstanceOf('Art4\JsonApiClient\AccessInterface', $link);
        $this->assertSame($link->getKeys(), ['about', 'meta', 'href']);

        $this->assertTrue($link->has('href'));
        $this->assertSame($link->get('href'), 'http://example.org/href');
        $this->assertTrue($link->has('meta'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('meta'));
        $this->assertTrue($link->has('about'));
        $this->assertSame($link->get('about'), 'http://example.org/about');

        $this->assertSame($link->asArray(), [
            'about' => $link->get('about'),
            'meta' => $link->get('meta'),
            'href' => $link->get('href'),
        ]);

        // TODO: Test full array
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

        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'ErrorLink MUST contain these properties: about'
        );

        $link->parse($object);
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

        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $link->parse($object);

        $this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);
        $this->assertSame($link->getKeys(), ['about']);

        $this->assertTrue($link->has('about'));
        $this->assertInstanceOf('Art4\JsonApiClient\LinkInterface', $link->get('about'));
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * The value of each links member MUST be an object (a "links object").
     *
     * @param mixed $input
     */
    public function testCreateWithDataprovider($input)
    {
        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

        // Input must be an object
        if (gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);

            return;
        }

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'Link has to be an object, "' . gettype($input) . '" given.'
        );

        $link->parse($input);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * test create without object or string attribute throws exception
     *
     * @param mixed $input
     */
    public function testCreateWithoutObjectOrStringAttributeThrowsException($input)
    {
        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

        // Input must be an object
        if (gettype($input) === 'string' or gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);

            return;
        }

        $object = new \stdClass();
        $object->about = 'http://example.org/about';
        $object->input = $input;

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'Link attribute has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @dataProvider jsonValuesProvider
     *
     * The value of the about member MUST be an object (a "links object") or a string.
     *
     * @param mixed $input
     */
    public function testAboutWithDataproviderThrowsException($input)
    {
        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));

        // Input must be string or object
        if (gettype($input) === 'string' or gettype($input) === 'object') {
            $this->assertInstanceOf('Art4\JsonApiClient\ErrorLink', $link);

            return;
        }

        $object = new \stdClass;
        $object->about = $input;

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\ValidationException',
            'Link has to be an object or string, "' . gettype($input) . '" given.'
        );

        $link->parse($object);
    }

    /**
     * @test
     */
    public function testGetOnANonExistingKeyThrowsException()
    {
        $object = new \stdClass();
        $object->about = 'http://example.org/about';

        $link = new ErrorLink($this->manager, $this->createMock('Art4\JsonApiClient\AccessInterface'));
        $link->parse($object);

        $this->assertFalse($link->has('something'));

        $this->setExpectedException(
            'Art4\JsonApiClient\Exception\AccessException',
            '"something" doesn\'t exist in this object.'
        );

        $link->get('something');
    }
}
