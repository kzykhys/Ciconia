---
layout: docs
title: Extend Ciconia
---

<h2 class="title">Extending Ciconia</h2>

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

class MentionExtension implements ExtensionInterface
{

    /**
     * {@inheritdoc}
     */
    public function register(\Ciconia\Markdown $markdown)
    {
        $markdown->on('inline', [$this, 'processMentions']);
    }

    /**
     * @param Text $text
     */
    public function processMentions(Text $text)
    {
        // Turn @username into [@username](http://example.com/user/username)
        $text->replace('/(?:^|[^a-zA-Z0-9.])@([A-Za-z]+[A-Za-z0-9]+)/', function (Text $w, Text $username) {
            return '[@' . $username . '](http://example.com/user/' . $username . ')';
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'mention';
    }
}
```

Register your extension.

``` php
<?php

require __DIR__ . '/vendor/autoload.php';

$ciconia = new \Ciconia\Ciconia();
$ciconia->addExtension(new MentionExtension());
echo $ciconia->render('@kzykhys my email address is example@example.com!');
```

Output

``` html
<p><a href="http://example.com/user/kzykhys">@kzykhys</a> my email address is example@example.com!</p>
```


Each extension handles string as a `Text` object. See [API section of kzykhys/Text][textapi].

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

[See the source code of Extensions](https://github.com/kzykhys/Ciconia/tree/master/src/Ciconia/Extension)

[See events and timing information](https://gist.github.com/kzykhys/7443440)

### Create your own Renderer

Ciconia supports HTML/XHTML output. but if you prefer customizing the output,
just create a class that implements `Ciconia\Renderer\RendererInterface`.

See [Ciconia\Renderer\RendererInterface](https://github.com/kzykhys/Ciconia/tree/master/src/Ciconia/Renderer/RendererInterface.php)