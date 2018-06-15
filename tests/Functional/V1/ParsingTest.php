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

namespace Art4\JsonApiClient\Tests\Functional\V1;

use Art4\JsonApiClient\Accessable;
use Art4\JsonApiClient\Input\RequestStringInput;
use Art4\JsonApiClient\Input\ResponseStringInput;
use Art4\JsonApiClient\Manager\ErrorAbortManager;
use Art4\JsonApiClient\Serializer\ArraySerializer;
use Art4\JsonApiClient\Tests\Fixtures\HelperTrait;
use Art4\JsonApiClient\V1\Attributes;
use Art4\JsonApiClient\V1\Document;
use Art4\JsonApiClient\V1\DocumentLink;
use Art4\JsonApiClient\V1\Factory;
use Art4\JsonApiClient\V1\Jsonapi;
use Art4\JsonApiClient\V1\Link;
use Art4\JsonApiClient\V1\Meta;
use Art4\JsonApiClient\V1\Relationship;
use Art4\JsonApiClient\V1\RelationshipCollection;
use Art4\JsonApiClient\V1\RelationshipLink;
use Art4\JsonApiClient\V1\ResourceCollection;
use Art4\JsonApiClient\V1\ResourceIdentifier;
use Art4\JsonApiClient\V1\ResourceIdentifierCollection;
use Art4\JsonApiClient\V1\ResourceItem;
use Art4\JsonApiClient\V1\ResourceItemLink;
use Art4\JsonApiClient\V1\ResourceNull;

class ParsingTest extends \Art4\JsonApiClient\Tests\Fixtures\TestCase
{
    use HelperTrait;

    /**
     * Provide JSON API data
     */
    public function jsonapiDataProvider()
    {
        $path = str_replace('/', \DIRECTORY_SEPARATOR, __DIR__ . '/../../files/');
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
     * @param mixed $parser
     */
    public function parseJsonapiDataWithErrorAbortManager($filename, array $meta)
    {
        $manager = new ErrorAbortManager(new Factory);

        $requestFiles = [
            '14_create_resource_without_id.json',
            '15_create_resource_without_id.json',
        ];

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
