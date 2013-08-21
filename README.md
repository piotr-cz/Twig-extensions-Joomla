Joomla Language Twig Extension
==============================

Extension provides new filter to allow using of Joomla-Framework Language package in [Twig][1] templates.


## Installation

Update your `composer.json`:

```JSON
{
	"require": {
		"piotr-cz/twig-extension-joomla": "dev-master"
	}
}
```


## Usage

It's assumed that you will use Composer to handle autoloading.

Add the extension to the twig environment:

```PHP
// Configure Twig
$loader = new \Twig_Loader_Filesystem(JPATH_TEMPLATES);
$twig = new \Twig_Environment($loader, $array());

// Register Extension
$twig->addExension(new \PiotrCz\TwigJoomla\Extension\TextExtension());

$template = $twig->loadTemplate('test.twig');
echo $template->render();
```


Extension is able to call any public method of Text (_, 'sprintf', ...).

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
