Ciconia - A New Markdown Parser for PHP
=======================================

[![Latest Stable Version](https://poser.pugx.org/kzykhys/ciconia/v/stable.png)](https://packagist.org/packages/kzykhys/ciconia)
[![Build Status](https://travis-ci.org/kzykhys/Ciconia.png?branch=master)](https://travis-ci.org/kzykhys/Ciconia)
[![Coverage Status](https://coveralls.io/repos/kzykhys/Ciconia/badge.png?branch=master)](https://coveralls.io/r/kzykhys/Ciconia?branch=master)

**This project is _unstable_** until 1.0.0 (see [milestones][milestones])

The Markdown parser for PHP5.4, it is fully extensible.
Ciconia is the collection of extension, so you can replace, add or remove each parsing mechanism.

*   Based on John Gruber's Markdown.pl

*   [Github Flavored Markdown](https://help.github.com/articles/github-flavored-markdown) support (disabled by default)

    * Multiple underscores in words
    * New lines
    * Fenced code blocks
    * Task lists

*   Tested with [karlcow/markdown-testsuite](https://github.com/karlcow/markdown-testsuite)

Requirements
------------

* PHP5.4+
* Composer

Installation
------------

create a `composer.json`

``` json
{
    "require": {
        "kzykhys/ciconia": "dev-master"
    }
}
```

and run

```
php composer.phar install
```

Usage
-----

### Traditional Markdown

``` php
use Ciconia\Ciconia;

$ciconia = new Ciconia();
$html = $ciconia->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

### Github Flavored Markdown

To activate 4 gfm features:

``` php
use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;

$ciconia = new Ciconia();
$ciconia->addExtension(new Gfm\FencedCodeBlockExtension());
$ciconia->addExtension(new Gfm\TaskListExtension());
$ciconia->addExtension(new Gfm\InlineStyleExtension());
$ciconia->addExtension(new Gfm\WhiteSpaceExtension());

$html = $ciconia->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

### Options

Option         | Type    | Default | Description                   |
---------------|---------|---------|-------------------------------|
**tabWidth**       | integer | 4       | Number of spaces              |
**nestedTagLevel** | integer | 3       | Max depth of nested HTML tags |

``` php
use Ciconia\Ciconia;

$ciconia = new Ciconia();
$html = $ciconia->render('Markdown is **awesome**', array('tabWidth' => 8, 'nestedTagLevel' => 5));
```

Rendering HTML or XHTML
-----------------------

Ciconia renders HTML by default. If you prefer XHTML:

``` php
use Ciconia\Ciconia;
use Ciconia\Renderer\XhtmlRenderer;

$ciconia = new Ciconia(new XhtmlRenderer());
$html = $ciconia->render('Markdown is **awesome**');

// <p>Markdown is <em>awesome</em></p>
```

Extend Ciconia
--------------

### How to Extend

Creating extension is easy, just implement `Ciconia\Extension\ExtensionInterface`.

Your class must implement 2 methods.

#### _void_ register(`Ciconia\Markdown` $markdown)

Register your callback to markdown event manager.
`Ciconia\Markdown` is instance of `Ciconia\Event\EmitterInterface` (looks like Node.js's EventEmitter)

#### _string_ getName()

Returns the name of your extension.
If your name is the same as one of core extension, it will be replaced by your extension.

### Extension Example

``` php
<?php

use Ciconia\Common\Text;
use Ciconia\Extension\ExtensionInterface;
use Ciconia\Markdown;

class YourExtension implements ExtensionInterface
{
    // Necessary if your extension calls other events
    private $markdown;

    //@implement
    public function register(Markdown $markdown)
    {
        // Necessary if your extension calls another event
        $this->markdown = $markdown;

        // Register a callback
        $markdown->on('block', array($this, 'processBlock'));
    }

    //@implement
    public function getName()
    {
        return 'yourExtension';
    }

    public function processBlock(Text $text)
    {
        // Do regexp's
        $text->replace('/foo(bar)(baz)/', function (Text $whole, Text $bar, Text $baz) {
            // You can call other events registered by other extension
            $this->markdown->emit('inline', $bar);

            return $baz->upper();
        });
    }
}
```

### Events

Possible events are:

| Event      | Description                                                                               |
|------------|-------------------------------------------------------------------------------------------|
| initialize | Document level parsing. Called at the first of the sequence.                              |
| block      | Block level parsing. Called after `initialize`                                            |
| inline     | Inline level parsing. Generally called by block level parsers.                            |
| detab      | Convert tabs to spaces. Generally called by block level parsers.                          |
| outdent    | Remove one level of line-leading tabs or spaces. Generally called by block level parsers. |
| finalize   | Called after `block`                                                                      |

[See the source code of Extensions](src/Ciconia/Extension)

### Create your own Renderer

Ciconia supports HTML/XHTML output. but if you prefer customizing the output,
just create a class that implements `Ciconia\Renderer\RendererInterface`.

See [Ciconia\Renderer\RendererInterface](src/Ciconia/Renderer/RendererInterface.php)

Command Line Interface
----------------------

### Usage

Basic Usage: (Outputs result to STDOUT)

    ciconia /path/to/file.md

Following command saves result to file:

    ciconia /path/to/file.md > /path/to/file.html

Or using pipe (On Windows in does't work):

    echo "Markdown is **awesome**" | ciconia

### Command Line Options

```
 --gfm                 Activate Gfm extensions
 --compress (-c)       Remove whitespace between HTML tags
 --format (-f)         Output format (html|xhtml) (default: "html")
 --lint (-l)           Syntax check only (lint)
```

### Where is the script?

CLI script will be installed in `vendor/bin/ciconia` by default.
To change the location:

> Yes, there are two ways an alternate vendor binary location can be specified:
>
> 1. Setting the bin-dir configuration setting in composer.json
> 2. Setting the environment variable COMPOSER_BIN_DIR

[http://getcomposer.org/doc/articles/vendor-binaries.md](http://getcomposer.org/doc/articles/vendor-binaries.md)

### Using PHAR version

You can also use [single phar file][phar]

```
ciconia.phar /path/to/file.md
```

If you prefer access this command globally, download [ciconia.phar][phar] and move it into your `PATH`.

```
mv ciconia.phar /usr/local/bin/ciconia
```

Testing
-------

Install or update `dev` dependencies.

```
php composer.phar update --dev
```

and run `phpunit`

License
-------

The MIT License

Contributing
------------

Feel free to folk this repository and send a pull request. ([A list of contributor][contributors])

Author
------

Kazuyuki Hayashi (@kzykhys)


[milestones]: https://github.com/kzykhys/Ciconia/issues/milestones
[phar]: https://github.com/kzykhys/Ciconia/releases/download/v0.1.4/ciconia.phar
[contributors]: https://github.com/kzykhys/Ciconia/graphs/contributors