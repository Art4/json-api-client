<?php

namespace Art4\JsonApiClient;

use Art4\JsonApiClient\Utils\MetaTrait;

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
	 * @throws \InvalidArgumentException
	 */
	public function __construct($object)
	{
		if ( ! is_object($object) )
		{
			throw new \InvalidArgumentException('$object has to be an object, "' . gettype($object) . '" given.');
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
	 * @param string $name The Name
	 *
	 * @return bool true if the link is set, false if not
	 */
	public function __isset($name)
	{
		if ( $name === 'meta' )
		{
			return $this->hasMeta();
		}

		return array_key_exists($name, $this->_links);
	}

	/**
	 * Get a link
	 *
	 * @param string $name The Name
	 *
	 * @return string|Link The link
	 */
	public function get($name)
	{
		if ( $name === 'meta' )
		{
			return $this->getMeta();
		}

		if ( ! $this->__isset($name) )
		{
			throw new \RuntimeException('You can\'t get "' . $name . '", because it wasn\'t set.');
		}

		return $this->_links[$name];
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
		}

		// from spec: an object ("link object") which can contain the following members:
		// - href: a string containing the link's URL.
		if ( $name === 'href' or ! is_object($link) )
		{
			$this->_links[$name] = (string) $link;

			return $this;
		}

		// Now $link can only be an object
		$this->_links[$name] = new Link($link);

		return $this;
	}
}
