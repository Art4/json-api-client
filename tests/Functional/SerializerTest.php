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

namespace Art4\JsonApiClient\Tests\Functional;

use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\Serializer\ArraySerializer;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\Tests\Fixtures\TestCase;
use Art4\JsonApiClient\V1\Factory;

class ParsingTest extends TestCase
{
    use HelperTrait;

    /**
     * Provide JSON API data
     */
    public function jsonapiDataProvider()
    {
        $path = str_replace('/', \DIRECTORY_SEPARATOR, __DIR__ . '/../files/');
        $files = [];

        $requestFiles = [
            '14_create_resource_without_id.json',
            '15_create_resource_without_id.json',
        ];

        foreach (glob($path . '*.json') as $file) {
            $filename = str_replace($path, '', $file);

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
        $manager = new ErrorAbortManager(new Factory);

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
