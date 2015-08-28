<?php

namespace Art4\JsonApiClient\Utils;

class Manager implements ManagerInterface, FactoryManagerInterface
{
	/**
	 * @var FactoryInterface
	 */
	protected $factory = null;

	/**
	 * Set a factory into the manager
	 *
	 * @param  FactoryInterface $factory
	 * @return object
	 */
	public function setFactory(FactoryInterface $factory)
	{
		$this->factory = $factory;

		return $this;
	}

	/**
	 * Get a factory from the manager
	 *
	 * @return FactoryInterface
	 */
	public function getFactory()
	{
		if ( is_null($this->factory) )
		{
			$this->setFactory(new Factory);
		}

		return $this->factory;
	}
}
