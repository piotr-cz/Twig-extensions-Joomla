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
	protected $jClass;

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
	 *
	 * @thorws \RuntimeException
	 */
	public function __construct($instance = null)
	{
		// Set handler
		if (is_object($instance)) {
			$this->jClass = $instance;
		}
		// Or use static methods
		else if (!class_exists($this->jClass)) {
			throw new \RuntimeException(sprintf('The %s class is required to use text-based filters.', $this->jClass));
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
	 *
	 * @throws \UnexpectedValueException
	 */
	public function filter($string, $method = null)
	{
		// Pick default method when none passed
		$method = $method ?: $this->defaultMethod;

		// Check if method is callable
		if (!is_callable(array($this->jClass, $method), false, $callable_name))
		{
			throw new \UnexpectedValueException(sprintf('Unable to execute method %s of %s.', $method, $this->getName()));
		}

		// Retrieve overloaded arguments without method
		$arguments = func_get_args();
		unset ($arguments[1]);

		// Execute
		return call_user_func_array(array($this->jClass, $method), $arguments);
	}

	/**
	 * Returns the name of the extension.
	 *
	 * @return string The extension name
	 */
	abstract public function getName();
}
