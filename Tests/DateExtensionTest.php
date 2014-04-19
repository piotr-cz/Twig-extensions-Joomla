<?php

namespace TwigJoomla\Extension;

use Joomla\Language\Language;
use Joomla\Language\Text;

/**
 * @author Piotr Konieczny <hello@piotr.cz>
 */
class DateExtensionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var DateExtension instance
	 */
	protected $object;

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new DateExtension;

		// Use Joomla language files
		defined('JPATH_ROOT') or define('JPATH_ROOT', __DIR__);
	}

	/**
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * @dataProvider getParseCzechTextTests
	 */
	public function testParseCzechText($template, $expected, $context = array())
	{
		// Load language
		$czechLanguage = Language::getInstance('cs-CZ');

		// Load cs-CZ.test.ini
		$czechLanguage->load('test', __DIR__);

		Text::setLanguage($czechLanguage);

		// Set timezone
		$this->object->setTimeZone('Europe/Prague');

		$this->assertEquals($expected, $this->getTemplate($template)->render($context));

		return;
	}

	/**
	 * Get Data sets
	 *
	 * @return array
	 */
	public function getParseCzechTextTests()
	{
		// First of June 2014, noon
		$testEvent = array('event' => array('on' => new \DateTime('06/01/2014 12:00:00')));
	
		return array(
			// Test tring format
			array(
				'{{ event.on|jdate(\'l, j. F Y\') }}', 
				'neděle, 1. červen 2014', 
				$testEvent,
			),
			// Test global timezone
			array(
				'{{ event.on|jdate(\'j. F\ G:i\') }}',
				'1. červen 14:00',
				$testEvent,
			),
			// Test timezone as argument
			array(
				'{{ event.on|jdate(\'g:i\', \'Europe/London\') }}',
				'1:00',
				$testEvent,
			)
		);
	}

	/**
	 * Setup Twig and prepare template
	 *
	 * @param string Template body
	 *
	 * @return object TwigTemplate object
	 */
	public function getTemplate($template)
	{
		$loader = new \Twig_Loader_Array(array('index' => $template));
		$twig = new \Twig_Environment($loader, array('debug' => true, 'cache' => false));
		$twig->addExtension($this->object);

		return $twig->loadTemplate('index');
	}
}