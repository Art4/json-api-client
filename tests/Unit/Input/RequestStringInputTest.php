<?php
/*
 * A PHP Library to handle a JSON API body in an OOP way.
 * Copyright (C) 2015-2021  Artur Weigandt  https://wlabs.de/kontakt

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

namespace Art4\JsonApiClient\Tests\Unit\Input;

use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;

class RequestStringInputTest extends TestCase
{
    use HelperTrait;

    /**
     * @test
     */
    public function testGetAsObjectFromStringReturnsObject()
    {
        $input = new RequestStringInput('{}');

        $this->assertInstanceOf(\stdClass::class, $input->getAsObject());
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     * @test
     *
     * @param mixed $input
     */
    public function testCreateWithoutStringThrowsException($input)
    {
        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            '$string must be a string, "' . gettype($input) . '" given.'
        );
        new RequestStringInput($input);
    }

    /**
     * @dataProvider jsonValuesAsStringProviderWithoutObject
     * @test
     *
     * @param string $input
     */
    public function testGetAsObjectWithInvalidStringsThrowsException(string $input)
    {
        $input = new RequestStringInput($input);

        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            'JSON must contain an object (e.g. `{}`).'
        );

        $input->getAsObject();
    }
}
