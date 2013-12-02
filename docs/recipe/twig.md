---
title: "Working with Twig - Ciconia (A Markdown Parser"
layout: docs
---

<h2 class="title">Working with Twig</h2>

<p>
  Twig is a modern template engine for PHP.
  Following example shows how to handle markdown contents inside your templates.
</p>

### Add a new Twig filter

This example translates a markdown string into HTML.

```
<div class="content">
    {{ "**Markdown** with _Twig_"|markdown }}
    {{ post.content|markdown }}
</div>
```

To implement `markdown` filter, we can use extension to integrate Ciconia into Twig.

``` php
use Ciconia\Ciconia;

class MarkdownExtension extends \Twig_Extension
{

    /**
     * @var Ciconia
     */
    private $ciconia;

    /**
     * @param Ciconia $ciconia
     */
    public function __construct(Ciconia $ciconia)
    {
        $this->ciconia = $ciconia;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('markdown', array($this, 'markdown'), array('is_safe' => array('html')))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'markdown';
    }

    /**
     * Renders HTML
     */
    public function markdown($string)
    {
        return $this->ciconia->render($string);
    }

}
```

