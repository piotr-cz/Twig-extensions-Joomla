<?php

namespace TwigJoomla\Extension;

use Joomla\Language\Language,
	Joomla\Language\Text
;

/**
 * @author Piotr Konieczny <hello@piotr.cz>
 */
class TextExtensionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var TextExtension instance
	 */
	protected $object;

	/**
	 * @var Joomla\Language\Language object
	 */
	protected $language;

	/**
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->object = new TextExtension;

		// Use Joomla language files
		defined('JPATH_BASE') or define('JPATH_BASE', __DIR__ );

		// Load language
		// Due to a bug, need to load default language ('en-GB')
		$this->language = Language::getInstance();
	}

	/**
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * @dataProvider getParseTextTests
	 */
	public function testParseText($template, $expected, $context = array())
	{
		// Load en-GB.test.ini
		$this->language->load('test', __DIR__);

		$this->assertEquals($expected, $this->getTemplate($template)->render($context));

		return;
	}

	/**
	 * Get Data sets
	 *
	 * @return array
	 */
	public function getParseTextTests()
	{
		return array(
			array('{{ "HELLO"|jtext }}', 'Hello'),
			array('{{ "HELLOW"|jtext(\'sprintf\', \'World\') }}', 'Hello World!'),
		);
	}

	/**
	 * @param string Template body
	 *
	 * @return object TwigTemplate object
	 */
	protected function getTemplate($template)
	{
		$loader = new \Twig_Loader_Array(array('index' => $template));
		$twig = new \Twig_Environment($loader, array('debug' => true, 'cache' => false));
		$twig->addExtension(new TextExtension());

		return $twig->loadTemplate('index');
	}
}
