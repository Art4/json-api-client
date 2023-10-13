<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Input;

use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use PHPUnit\Framework\TestCase;

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
