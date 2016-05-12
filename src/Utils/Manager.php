<?php

namespace Art4\JsonApiClient\Utils;

final class Manager implements ManagerInterface, FactoryManagerInterface
{
	/**
	 * @var FactoryInterface
	 */
	public $factory = null;

	/**
	 * @param  FactoryInterface $factory
	 * @return object
	 */
	public function __construct($factory = null)
	{
		if ( ! is_null($factory) )
		{
			$this->setFactory($factory);
		}
	}

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
	 * @return  Art4\JsonApiClient\AccessInterface
	 *
	 * @throws  Art4\JsonApiClient\Exception\ValidationException If $string is not valid JSON API
	 */
	public function parse($string)
	{
		$object = Helper::decodeJson($string);

		$document = $this->getFactory()->make('Document', [
			$this,
		]);

		$document->parse($object);

		return $document;
	}
}
