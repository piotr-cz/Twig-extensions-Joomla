Joomla Language Twig Extension
==============================

Extension provides new filter to allow using of [Joomla-Framework][2] [Language package][3] in [Twig][1] templates.


## Installation

Update requires in your `composer.json`:

```JSON
	"require": {
		"joomla/language": "dev-master",
		"piotr-cz/twig-extensions-joomla": "dev-master"
	}
```

and add repository manually as the it's not a [packagist](https://packagist.org) yet:
```JSON

	"repositories"		: [
		{
			"type"			: "package",
			"package"		: {
				"name"			: "piotr-cz/twig-extensions-joomla",
				"version"		: "master",
				"source"		: {
					"url"			: "https://github.com/piotr-cz/Twig-extensions-Joomla.git",
					"type"			: "git",
					"reference"		: "master"
				},
				"autoload"		: {
					"psr-0"				: { "TwigJoomla"	: ["src/"] }
				}
			}
		}
		
	]
```


## Usage

It's assumed that you will use Composer to handle autoloading.

Add the extension to the twig environment:

```PHP
// Configure Twig
$loader = new \Twig_Loader_Filesystem(JPATH_TEMPLATES);
$twig = new \Twig_Environment($loader, $array());

// Register Extension
$twig->addExension(new \TwigJoomla\Extension\TextExtension);

$template = $twig->loadTemplate('test.twig');
echo $template->render();
```


Extension is able to call any public method of Text (like `Text::_()`, `Text::sprintf()`, ...).

Use filter in your templates:


### Text

Twig Template:
```TWIG
{{ "IMADEIT"|jtext }}
```

Language file:
```INI
IMADEIT 	= "I made it!"
```

Result:
```
I made it!
```


### Sprintf

Twig template:
```TWIG
{{ "HELLOW"|jtext('sprintf', 'World') }}
```

Language file:
```INI
HELLOW		="Hello %s!"
```

Result:
```
Hello World!
```


## Licence
This extension is released under the MIT License


[1]: http://twig.sensiolabs.org
[2]: http://framework.joomla.org
[3]: https://github.com/joomla/joomla-framework/tree/staging/src/Joomla/Language
