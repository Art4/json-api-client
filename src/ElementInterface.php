<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\FactoryManagerInterface;

/**
 * Element Interface
 */
interface ElementInterface extends AccessInterface
{
	public function __construct(FactoryManagerInterface $manager, AccessInterface $parent);

	public function parse($object);
}
