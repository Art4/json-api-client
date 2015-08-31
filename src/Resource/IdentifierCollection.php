<?php

namespace Art4\JsonApiClient\Resource;

use Art4\JsonApiClient\AccessInterface;
use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * A Identifier Collection holds only Identifier
 */
class IdentifierCollection extends Collection
{
	/**
	 * Generate a new resource from an object
	 *
	 * @param object $data The resource data
	 * @return ResourceInterface The resource
	 */
	protected function parseResource($data)
	{
		return $this->manager->getFactory()->make(
			'Resource\Identifier',
			[$data, $this->manager]
		);
	}
}
