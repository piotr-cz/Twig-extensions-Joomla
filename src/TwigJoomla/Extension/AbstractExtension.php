<?php

/*
 * This file is part of Twig.
 *
 * (c) 2013 Piotr Konieczny
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace TwigJoomla\Extension;

/**
 * Abstract extension class for Joomla packages
 */
abstract class AbstractExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
	/**
	 * Joomla-Framework class to call
	 *
	 * @var string
	 */
	protected $jclass;

	/**
	 * Default method when none provided
	 *
	 * @var string
	 */
	protected $defaultMethod;

	/**
	 * Constructor.
	 *
	 * @param $instance [optional]
	 */
	public function __construct($instance = null)
	{
		if (!class_exists($this->jclass)) {
			throw new \RuntimeException(sprintf('The %s class is needed to use text-based filters.', $this->jclass));
		}
	}

	/**
	 * Returns a list of filters to add to the existing list.
	 *
	 * @return array And array of filters
	 */
	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter($this->getName(), array($this, 'filter'))
		);
	}

	/**
	 * Execute method
	 *
	 * Note that this method can take a mixed number of arguments
	 *
	 * @param string $method Class method
	 * @param string $string String to transform
	 *
	 * @return string Transformed
	 */
	public function filter($string, $method = null)
	{
		// Pick default method when none passed
		$method = $method ?: $this->defaultMethod;

		// Check if method is callable
		if (!is_callable(array($this->jclass, $method), false, $callable_name))
		{
			throw new \UnexpectedValueException(sprintf('Cannot execute method %s.', $method));
		}

		$arguments = func_get_args();
		unset ($arguments[1]);

		// Execute
		return call_user_func_array(array($this->jclass, $method), $arguments);
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	abstract public function getName();
}
