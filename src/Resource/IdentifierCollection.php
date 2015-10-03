<?php

namespace Art4\JsonApiClient\Resource;

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
