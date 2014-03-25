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

use Joomla\Language\Text
;

/**
 * Joomla Text bridge
 *
 * Available methods are '_'|'alt'|'plural'|'sprintf'|'printf'|'script'
 */
class TextExtension extends AbstractExtension
{
	/**
	 * @inheritDoc
	 */
	protected $jclass = '\\Joomla\\Language\\Text';

	/**
	 * @inheritDoc
	 */
	protected $defaultMethod = '_';

	/**
	 * @inheritDoc
	 */
	public function getName()
	{
		return 'jtext';
	}
}
