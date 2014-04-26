<?php

/*
 * (c) 2014 Piotr Konieczny
 *
 * Backport of JDate functionality cut off from Joomla-Framework/Date package
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @see         Joomla-Platform/JDate https://github.com/joomla/joomla-platform/blob/staging/libraries/joomla/date/date.php
 */

namespace TwigJoomla\Extension;

use Joomla\Language\Text;

/**
 * Joomla Date bridge
 *
 * @since 1.1
 */
class DateExtension extends \Twig_Extension implements \Twig_ExtensionInterface
{
	const DAY_ABBR = "\x021\x03";
	const DAY_NAME = "\x022\x03";
	const MONTH_ABBR = "\x023\x03";
	const MONTH_NAME = "\x024\x03";

	/**
	 * Default format
	 *
	 * @var string
	 * @since 1.1
	 */
	protected $format = 'Y-m-d H:i:s';

	/**
	 * Default timezone
	 *
	 * @var \DateTimeZone
	 * @since 1.1
	 */
	protected $timezone;

	/**
	 * @inheritDoc
	 *
	 * @since 1.1
	 */
	public function __construct($timezone = null)
	{
		$this->setTimeZone($timezone ?: 'UTC');
	}

	/**
	 * Set global time zone
	 *
	 * @param string|\DateTimeZone $timezone
	 *
	 * @return DateExtension class instance to allow chaining
	 *
	 * @since 1.1
	 * @throws \Exception Unknown or bad 
	 */
	public function setTimeZone($timezone)
	{
		$this->timezone = ($timezone instanceof \DateTimeZone)
			? $timezone
			: new \DateTimeZone($timezone);

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter($this->getName(), array($this, 'format'))
		);
	}

	/**
	 * Format to string
	 *
	 * @param string|\DateTime $dateTime
	 * @param string|\DateTimeZone $format
	 *
	 * @return string
	 *
	 * @since 1.1
	 * @throws \Exception failed to parse string
	 */
	public function format($dateTime, $format = null, $timezone = null)
	{
		// Do string replacements for date format options that can be translated.
		$format = preg_replace('/(^|[^\\\])D/', "\\1" . self::DAY_ABBR, $format);
		$format = preg_replace('/(^|[^\\\])l/', "\\1" . self::DAY_NAME, $format);
		$format = preg_replace('/(^|[^\\\])M/', "\\1" . self::MONTH_ABBR, $format);
		$format = preg_replace('/(^|[^\\\])F/', "\\1" . self::MONTH_NAME, $format);


		// Clone to allow timezone operations
		if ($dateTime instanceof \DateTime)
		{
			$dateTimeInZone = clone $dateTime;
		}
		// Or create from string
		else
		{
			$dateTimeInZone = new \DateTime($dateTime);
		}

		// Set timezone string argument
		if ($timezone instanceof \DateTimeZone)
		{
			$dateTimeInZone->setTimeZone($timezone);
		}
		// Set timezone DateTimeZone argument
		else if (is_string($timezone))
		{
			$dateTimeInZone->setTimeZone(new \DateTimeZone($timezone));
		}
		// Or global class option
		else
		{
			$dateTimeInZone->setTimeZone($this->timezone);
		}


		// Format to string
		$return = $dateTimeInZone->format($format ?: $this->format);


		// Manually modify the month and day strings in the formatted time.
		if (strpos($return, self::DAY_ABBR) !== false)
		{
			$return = str_replace(self::DAY_ABBR, $this->dayToString($dateTimeInZone->format('w'), true), $return);
		}

		if (strpos($return, self::DAY_NAME) !== false)
		{
			$return = str_replace(self::DAY_NAME, $this->dayToString($dateTimeInZone->format('w')), $return);
		}

		if (strpos($return, self::MONTH_ABBR) !== false)
		{
			$return = str_replace(self::MONTH_ABBR, $this->monthToString($dateTimeInZone->format('n'), true), $return);
		}

		if (strpos($return, self::MONTH_NAME) !== false)
		{
			$return = str_replace(self::MONTH_NAME, $this->monthToString($dateTimeInZone->format('n')), $return);
		}


		return $return;
	}

	/**
	 * Translates day of week number to a string.
	 *
	 * @param   integer  $day   The numeric day of the week.
	 * @param   boolean  $abbr  Return the abbreviated day string?
	 *
	 * @return  string  The day of the week.
	 *
	 * @since   1.1
	 */
	public function dayToString($day, $abbr = false)
	{
		switch ($day)
		{
			case 0:
				return $abbr ? Text::_('SUN') : Text::_('SUNDAY');
			case 1:
				return $abbr ? Text::_('MON') : Text::_('MONDAY');
			case 2:
				return $abbr ? Text::_('TUE') : Text::_('TUESDAY');
			case 3:
				return $abbr ? Text::_('WED') : Text::_('WEDNESDAY');
			case 4:
				return $abbr ? Text::_('THU') : Text::_('THURSDAY');
			case 5:
				return $abbr ? Text::_('FRI') : Text::_('FRIDAY');
			case 6:
				return $abbr ? Text::_('SAT') : Text::_('SATURDAY');
		}
	}

	/**
	 * Translates month number to a string.
	 *
	 * @param   integer  $month  The numeric month of the year.
	 * @param   boolean  $abbr   If true, return the abbreviated month string
	 *
	 * @return  string  The month of the year.
	 *
	 * @since   1.1
	 */
	public function monthToString($month, $abbr = false)
	{
		switch ($month)
		{
			case 1:
				return $abbr ? Text::_('JANUARY_SHORT') : Text::_('JANUARY');
			case 2:
				return $abbr ? Text::_('FEBRUARY_SHORT') : Text::_('FEBRUARY');
			case 3:
				return $abbr ? Text::_('MARCH_SHORT') : Text::_('MARCH');
			case 4:
				return $abbr ? Text::_('APRIL_SHORT') : Text::_('APRIL');
			case 5:
				return $abbr ? Text::_('MAY_SHORT') : Text::_('MAY');
			case 6:
				return $abbr ? Text::_('JUNE_SHORT') : Text::_('JUNE');
			case 7:
				return $abbr ? Text::_('JULY_SHORT') : Text::_('JULY');
			case 8:
				return $abbr ? Text::_('AUGUST_SHORT') : Text::_('AUGUST');
			case 9:
				return $abbr ? Text::_('SEPTEMBER_SHORT') : Text::_('SEPTEMBER');
			case 10:
				return $abbr ? Text::_('OCTOBER_SHORT') : Text::_('OCTOBER');
			case 11:
				return $abbr ? Text::_('NOVEMBER_SHORT') : Text::_('NOVEMBER');
			case 12:
				return $abbr ? Text::_('DECEMBER_SHORT') : Text::_('DECEMBER');
		}
	}

	/**
	 * @inheritDoc
	 *
	 * @since 1.1
	 */
	public function getName()
	{
		return 'jdate';
	}
}
