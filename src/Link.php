<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\PaginationLink;
use Art4\JsonApiClient\Utils\MetaTrait;
use Art4\JsonApiClient\Exception\ValidationException;

/**
 * Link Object
 *
 * @see http://jsonapi.org/format/#document-links
 */
class Link
{
	use MetaTrait;

	protected $_links = array();

	/**
	 * @param object $object The error object
	 *
	 * @return self
	 *
	 * @throws ValidationException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new ValidationException('Link has to be an object, "' . gettype($object) . '" given.');
		}

		$object_vars = get_object_vars($object);

		if ( count($object_vars) === 0 )
		{
			return $this;
		}

		foreach ($object_vars as $name => $value)
		{
			$this->set($name, $value);
		}

		return $this;
	}

	/**
	 * Is a link set?
	 *
	 * @param string $key The Key
	 *
	 * @return bool true if the link is set, false if not
	 */
	public function has($key)
	{
		if ( $key === 'meta' )
		{
			return $this->hasMeta();
		}

		return array_key_exists($key, $this->_links);
	}

	/**
	 * Returns the keys of all setted values
	 *
	 * @return array Keys of all setted values
	 */
	public function getKeys()
	{
		$keys = array_keys($this->_links);

		if ( $this->has('meta') )
		{
			$keys[] = 'meta';
		}

		return $keys;
	}

	/**
	 * Get a link
	 *
	 * @param string $key The Name
	 *
	 * @return string|Link The link
	 */
	public function get($key)
	{
		if ( ! $this->has($key) )
		{
			throw new \RuntimeException('"' . $key . '" doesn\'t exist in this object.');
		}

		if ( $key === 'meta' )
		{
			return $this->getMeta();
		}

		return $this->_links[$key];
	}

	/**
	 * Set a link
	 *
	 * @param string $name The Name
	 * @param string|object $link The Link
	 *
	 * @return self
	 */
	protected function set($name, $link)
	{
		if ( $name === 'meta' )
		{
			$this->setMeta($link);

			return $this;
		}

		// from spec: an object ("link object") which can contain the following members:
		// - href: a string containing the link's URL.
		if ( $name === 'href' or ! is_object($link) )
		{
			// Pagination: Keys MUST either be omitted or have a null value to indicate that a particular link is unavailable.
			if ( is_null($link) and ($this instanceof PaginationLink) )
			{
				return $this;
			}

			if ( ! is_string($link) )
			{
				throw new ValidationException('Link has to be an object or string, "' . gettype($link) . '" given.');
			}

			$this->_links[$name] = strval($link);

			return $this;
		}

		// Now $link can only be an object
		// Create Link object if needed
		if ( ! ($link instanceof Link) )
		{
			$link = new Link($link);
		}

		$this->_links[$name] = $link;

		return $this;
	}
}
