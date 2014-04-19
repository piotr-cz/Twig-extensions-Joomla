Joomla Language Twig Extension
==============================

[![Build Status](https://travis-ci.org/piotr-cz/Twig-extensions-Joomla.svg?branch=master)](https://travis-ci.org/piotr-cz/Twig-extensions-Joomla)

Extensions provide filters to allow using of [Joomla-Framework][2] packages such as [Language package][3] in [Twig][1] templates.


## Installation

Add `"piotr-cz/twig-extensions-joomla": "~1.0"` to require block in your composer.json and run `composer install`.

```json
{
	"require": {
		"piotr-cz/twig-extensions-joomla": "~1.0"
	}
}
```


## Text extension

It's assumed that you will use Composer to handle autoloading.

### Setup

Add the extension to the twig environment:

```php
// Configure Twig
$loader = new \Twig_Loader_Filesystem(JPATH_TEMPLATES);
$twig = new \Twig_Environment($loader, $options = array());

// Register Extension
$twig->addExtension(new \TwigJoomla\Extension\TextExtension);

// Render Template
$template = $twig->loadTemplate('test.twig');
echo $template->render();
```


Extension is able to call any public method of Text (like `Text::_()`, `Text::sprintf()`, ...).

Use filter in your templates:


### The `_` method

Twig Template:
```twig
{{ "IMADEIT" | jtext }}
```

Language file:
```ini
IMADEIT 	= "I made it!"
```

Result:
```
I made it!
```


### The `sprintf` method

Twig template:
```twig
{{ "HELLOW" | jtext('sprintf', 'World') }}
```

Language file:
```ini
HELLOW		="Hello %s!"
```

Result:
```
Hello World!
```


## Date extension

This extension allows to use localised date output and instead of [PHP intl](http://php.net/intl) extension uses [Language package][3].
Crucial functions imported from of [JDate package](https://github.com/joomla/joomla-platform/blob/staging/libraries/joomla/date/date.php).

### Setup

```php
$twig->addExtension(new \Twigoomla\Extension\DateExtension($config->get('timezone')));
```

### Usage

Twig template:
```twig
{{ event.on | jdate('l, j. F Y') }}
```

Language File:
```ini
SUNDAY		="neděle"
JUNE		="červen"
```

Result:
```
neděle, 1. červen 2014
```



## Application setup

See [Joomla-Framework/Language][3] package for instructions on how to setup the `Language` package in your application.


## Running tests

```sh
$ composer install
$ phpunit
```

## Licence
This extensions are released under the MIT License, except Date extension which is released under GPL2


[1]: http://twig.sensiolabs.org
[2]: http://framework.joomla.org
[3]: https://github.com/joomla-framework/language
