<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\AccessTrait;
use Art4\JsonApiClient\Utils\DataContainer;
use Art4\JsonApiClient\Utils\FactoryManagerInterface;
use Art4\JsonApiClient\Exception\AccessException;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Document Link Object
 *
 * @see http://jsonapi.org/format/#document-top-level
 *
 * The top-level links object MAY contain the following members:
 * - self: the link that generated the current response document.
 * - related: a related resource link when the primary data represents a resource relationship.
 * - pagination links for the primary data
 */
final class DocumentLink implements DocumentLinkInterface
{
	use AccessTrait;

	/**
	 * @var DataContainerInterface
	 */
	protected $container;

	/**
	 * @var FactoryManagerInterface
	 */
	protected $manager;

	/**
	 * @param object $object The link object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('DocumentLink has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		$this->container = new DataContainer();

		$links = get_object_vars($object);

		if ( array_key_exists('self', $links) )
		{
			if ( ! is_string($links['self']) )
			{
				throw new ValidationException('property "self" has to be a string, "' . gettype($links['self']) . '" given.');
			}

			$this->container->set('self', $links['self']);

			unset($links['self']);
		}

		if ( array_key_exists('related', $links) )
		{
			if ( ! is_string($links['related']) )
			{
				throw new ValidationException('property "related" has to be a string, "' . gettype($links['related']) . '" given.');
			}

			$this->container->set('related', $links['related']);

			unset($links['related']);
		}

		// Pagination links

		if ( array_key_exists('first', $links) )
		{
			$this->setPaginationLink('first', $links['first']);

			unset($links['first']);
		}

		if ( array_key_exists('last', $links) )
		{
			$this->setPaginationLink('last', $links['last']);

			unset($links['last']);
		}

		if ( array_key_exists('prev', $links) )
		{
			$this->setPaginationLink('prev', $links['prev']);

			unset($links['prev']);
		}

		if ( array_key_exists('next', $links) )
		{
			$this->setPaginationLink('next', $links['next']);

			unset($links['next']);
		}

		// custom links
		foreach ($links as $name => $value)
		{
			$this->setLink($name, $value);
		}
	}

	/**
	 * Get a value by the key of this object
	 *
	 * @param string $key The key of the value
	 * @return mixed The value
	 */
	public function get($key)
	{
		try
		{
			return $this->container->get($key);
		}
		catch (AccessException $e)
		{
			throw new AccessException('"' . $key . '" doesn\'t exist in this object.');
		}
	}

	/**
	 * Set a pagination link
	 *
	 * @param string $name The name of the link
	 * @param string $value The link
	 * @return self
	 */
	private function setPaginationLink($name, $value)
	{
		if ( ! is_string($value) and ! is_null($value) )
		{
			throw new ValidationException('property "' . $name . '" has to be a string or null, "' . gettype($value) . '" given.');
		}

		if ( ! is_null($value) )
		{
			$this->container->set($name, strval($value));
		}

		return $this;
	}

	/**
	 * Set a link
	 *
	 * @param string $name The name of the link
	 * @param string $link The link
	 * @return self
	 */
	private function setLink($name, $link)
	{
		if ( ! is_string($link) and ! is_object($link) )
		{
			throw new ValidationException('Link has to be an object or string, "' . gettype($link) . '" given.');
		}

		if ( $name === 'meta' )
		{
			$this->container->set($name, $this->manager->getFactory()->make(
				'Meta',
				[$link, $this->manager]
			));

			return $this;
		}

		if ( is_string($link) )
		{
			$this->container->set($name, strval($link));

			return $this;
		}

		// Now $link can only be an object
		$this->container->set($name, $this->manager->getFactory()->make(
			'Link',
			[$link, $this->manager]
		));

		return $this;
	}
}
