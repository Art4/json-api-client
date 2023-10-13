<?php

// SPDX-FileCopyrightText: 2015-2023 Artur Weigandt https://wlabs.de/kontakt
//
// SPDX-License-Identifier: GPL-3.0-or-later

namespace Art4\JsonApiClient\Tests\Functional;

use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\Serializer\ArraySerializer;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\Factory;
use PHPUnit\Framework\TestCase;

class SerializerTest extends TestCase
{
    use HelperTrait;

    /**
     * Provide JSON API data
     */
    public static function jsonapiDataProvider(): array
    {
        $path = str_replace('/', \DIRECTORY_SEPARATOR, __DIR__ . '/../files/');
        $files = [];

        $requestFiles = [
            '14_create_resource_without_id.json',
            '15_create_resource_without_id.json',
        ];

        foreach (glob($path . '*.json') as $file) {
            $filename = str_replace($path, '', $file);

            // Ignore files with errors
            if (in_array($filename, [
                '16_type_and_id_as_integer.json'
            ])) {
                continue;
            }

            $files[] = [
                $filename,
                [
                    'is_request' => in_array($filename, $requestFiles)
                ],
            ];
        }

        return $files;
    }

    /**
     * @test
     * @dataProvider jsonapiDataProvider
     *
     * @param mixed $filename
     */
    public function parseJsonapiDataWithErrorAbortManager($filename, array $meta)
    {
        $manager = new ErrorAbortManager(new Factory());

        $string = $this->getJsonString($filename);

        if ($meta['is_request']) {
            $input = new RequestStringInput($string);
        } else {
            $input = new ResponseStringInput($string);
        }

        $document = $manager->parse($input);

        // Test full array
        $this->assertEquals(
            json_decode($string, true),
            (new ArraySerializer(['recursive' => true]))->serialize($document),
            $filename
        );
    }
}
