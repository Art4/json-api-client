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
	 * @var AccessInterface
	 */
	protected $parent;

	/**
	 * @param object $object The link object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object, FactoryManagerInterface $manager, AccessInterface $parent)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('DocumentLink has to be an object, "' . gettype($object) . '" given.');
		}

		$this->manager = $manager;

		$this->parent = $parent;

		$this->container = new DataContainer();

		$links = get_object_vars($object);

		if ( array_key_exists('self', $links) )
		{
			if ( ! is_string($links['self']) and ! is_object($links['self']) )
			{
				throw new ValidationException('property "self" has to be a string or object, "' . gettype($links['self']) . '" given.');
			}

			$this->setLink('self', $links['self']);

			unset($links['self']);
		}

		if ( array_key_exists('related', $links) )
		{
			if ( ! is_string($links['related']) and ! is_object($links['related']) )
			{
				throw new ValidationException('property "related" has to be a string or object, "' . gettype($links['related']) . '" given.');
			}

			$this->setLink('related', $links['related']);

			unset($links['related']);
		}

		// Pagination links, if data in parent attributes exists
		if ( $parent->has('data') )
		{
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
		if ( ! is_object($value) and ! is_string($value) and ! is_null($value) )
		{
			throw new ValidationException('property "' . $name . '" has to be an object, a string or null, "' . gettype($value) . '" given.');
		}

		if ( is_string($value) )
		{
			$this->container->set($name, strval($value));
		}
		elseif ( is_object($value) )
		{
			$this->setLink($name, $value);
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
			throw new ValidationException('Link attribute has to be an object or string, "' . gettype($link) . '" given.');
		}

		if ( is_string($link) )
		{
			$this->container->set($name, strval($link));

			return $this;
		}

		// Now $link can only be an object
		$this->container->set($name, $this->manager->getFactory()->make(
			'Link',
			[$link, $this->manager, $this]
		));

		return $this;
	}
}
