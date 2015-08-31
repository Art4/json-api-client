<?php

namespace Art4\JsonApiClient\Utils;

/**
 * Manager Interface
 */
interface FactoryManagerInterface
{
	/**
	 * Set a factory into the manager
	 *
	 * @param  FactoryInterface $factory
	 * @return object
	 */
	public function setFactory(FactoryInterface $factory);

	/**
	 * Get a factory from the manager
	 *
	 * @return FactoryInterface
	 */
	public function getFactory();
}
