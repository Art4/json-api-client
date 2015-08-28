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

	/**
	 * Parse a JSON API string into an object
	 *
	 * @param   string $string The JSON API string
	 *
	 * @throws  Art4\JsonApiClient\Exception\ValidationException If $string is not valid JSON API
	 *
	 * @return  Art4\JsonApiClient\AccessInterface
	 */
	public function parse($string)
	{
		$object = Helper::decodeJson($string);

		return $this->getFactory()->make('Document', [
			$object,
			$this,
		]);
	}
}
