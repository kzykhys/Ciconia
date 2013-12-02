---
title: "Redistribute Cicona as your markdown parser - Ciconia (A Markdown Parser"
layout: docs
---

<h2 class="title">Redistribute Cicona as your markdown parser</h2>

<p>Ciconia is licensed under MIT. That means you can include this library into any kind of projects.</p>
<p>
  And Ciconia only supports official markdown syntax by default.
  If it don't satisfy you (or your clients), You can redistribute Ciconia with your favorite extensions.
</p>

### Example 1: Activate Gfm by default

Just override `getDefaultExtensions` inside your subclass.

``` php
use Ciconia\Extension\Gfm;

class YourMarkdownParser extends Ciconia
{
    protected function getDefaultExtensions()
    {
        return array_merge(
            parent::getDefaultExtensions(),
            [
                new Gfm\FencedCodeBlockExtension(),
                new Gfm\TaskListExtension(),
                new Gfm\InlineStyleExtension(),
                new Gfm\WhiteSpaceExtension(),
                new Gfm\TableExtension(),
                new Gfm\UrlAutoLinkExtension(),
            ]
        );
    }
}
```