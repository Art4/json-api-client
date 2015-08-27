<?php

namespace Art4\JsonApiClient\Utils;

use Art4\JsonApiClient\Exception\FactoryException;

class Factory implements FactoryInterface
{
	/**
	 * @var array
	 */
	protected $classes = [
		'Attributes'             => 'Art4\JsonApiClient\Attributes',
		'Document'               => 'Art4\JsonApiClient\Document',
		'DocumentLink'           => 'Art4\JsonApiClient\DocumentLink',
		'Error'                  => 'Art4\JsonApiClient\Error',
		'ErrorCollection'        => 'Art4\JsonApiClient\ErrorCollection',
		'ErrorLink'              => 'Art4\JsonApiClient\ErrorLink',
		'ErrorSource'            => 'Art4\JsonApiClient\ErrorSource',
		'Jsonapi'                => 'Art4\JsonApiClient\Jsonapi',
		'Link'                   => 'Art4\JsonApiClient\Link',
		'Meta'                   => 'Art4\JsonApiClient\Meta',
		'PaginationLink'         => 'Art4\JsonApiClient\PaginationLink',
		'Relationship'           => 'Art4\JsonApiClient\Relationship',
		'RelationshipCollection' => 'Art4\JsonApiClient\RelationshipCollection',
		'RelationshipLink'       => 'Art4\JsonApiClient\RelationshipLink',
		'Resource\Collection'    => 'Art4\JsonApiClient\Resource\Collection',
		'Resource\Identifier'    => 'Art4\JsonApiClient\Resource\Identifier',
		'Resource\Item'          => 'Art4\JsonApiClient\Resource\Item',
		'Resource\NullResource'  => 'Art4\JsonApiClient\Resource\NullResource',
	];

	/**
	 * @param array $overload Specs to be overloaded with custom classes.
	 */
	public function __construct(array $overload = [])
	{
		if ($overload)
		{
			$this->classes = array_replace($this->classes, $overload);
		}
	}

	/**
	 * Create a new instance of a class
	 *
	 * @param  string $name
	 * @param  array  $args
	 * @return object
	 */
	public function make($name, array $args = [])
	{
		if ( ! isset($this->classes[$name]) )
		{
			throw new FactoryException('"' . $name . '" is not a registered class');
		}

		$class = new \ReflectionClass($this->classes[$name]);

		return $class->newInstanceArgs($args);
	}
}
