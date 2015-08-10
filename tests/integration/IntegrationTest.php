<?php

namespace Art4\JsonApiClient\Integration\Tests;

use Art4\JsonApiClient\Utils\Helper;
use InvalidArgumentException;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * returns a json string
	 */
	protected function getJsonString($filename)
	{
		$content = file_get_contents(__DIR__ . '/../files/' . $filename);

		return $content;
	}

	/**
	 * @test parse() with valid JSON API returns Document Object
	 */
	public function testParseSimpleResource()
	{
		$string = $this->getJsonString('01_simple_resource.js');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->hasErrors());
		$this->assertFalse($document->hasMeta());
		$this->assertFalse($document->hasJsonapi());
		$this->assertFalse($document->hasLinks());
		$this->assertFalse($document->hasIncluded());
		$this->assertTrue($document->hasData());

		$resource = $document->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\Resource', $resource);
		$this->assertFalse($resource->hasMeta());
		$this->assertSame($resource->getType(), 'articles');
		$this->assertSame($resource->getId(), '1');
		$this->assertTrue($resource->hasAttributes());
		$this->assertInstanceOf('Art4\JsonApiClient\Attributes',  $resource->getAttributes());
		$this->assertTrue($resource->hasRelationships());
		$this->assertInstanceOf('Art4\JsonApiClient\RelationshipCollection',  $resource->getRelationships());
	}

	/**
	 * @test parse() with valid JSON API returns Document Object
	 */
	public function testParseSimpleResourceIdentifier()
	{
		$string = $this->getJsonString('02_simple_resource_identifier.js');
		$document = Helper::parse($string);

		$this->assertInstanceOf('Art4\JsonApiClient\Document', $document);
		$this->assertFalse($document->hasErrors());
		$this->assertFalse($document->hasMeta());
		$this->assertFalse($document->hasJsonapi());
		$this->assertFalse($document->hasLinks());
		$this->assertFalse($document->hasIncluded());
		$this->assertTrue($document->hasData());

		$resource = $document->getData();

		$this->assertInstanceOf('Art4\JsonApiClient\ResourceIdentifier', $resource);
		$this->assertFalse($resource->hasMeta());
		$this->assertSame($resource->getType(), 'articles');
		$this->assertSame($resource->getId(), '1');
	}
}
