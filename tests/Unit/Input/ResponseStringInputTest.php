<?php

declare(strict_types=1);

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Unit\Input;

use Art4\JsonApiClient\Exception\InputException;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use PHPUnit\Framework\TestCase;

class ResponseStringInputTest extends TestCase
{
    use HelperTrait;

    public function testGetAsObjectFromStringReturnsObject(): void
    {
        $input = new ResponseStringInput('{}');

        $this->assertInstanceOf(\stdClass::class, $input->getAsObject());
    }

    /**
     * @dataProvider jsonValuesProviderWithoutString
     */
    public function testCreateWithoutStringThrowsException(mixed $input): void
    {
        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            '$string must be a string, "' . gettype($input) . '" given.'
        );
        new ResponseStringInput($input);
    }

    /**
     * @dataProvider jsonValuesAsStringProviderWithoutObject
     */
    public function testGetAsObjectWithInvalidStringsThrowsException(string $input): void
    {
        $input = new ResponseStringInput($input);

        $this->expectException(InputException::class);
        $this->expectExceptionMessage(
            'JSON must contain an object (e.g. `{}`).'
        );

        $input->getAsObject();
    }
}
