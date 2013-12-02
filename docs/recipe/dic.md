---
title: "Using DI Container to manage extensions - Ciconia (A Markdown Parser"
layout: docs
---

<h2 class="title">Using DI Container to manage extensions</h2>

<ul style="font-size:18px;line-height: 1.5;">
  <li><a href="#symfony">Symfony</a></li>
  <li><a href="#pimple">Pimple/Silex</a></li>
  <li><a href="#bowl">Bowl</a></li>
</ul>

<h2 id="symfony">Symfony</h2>

### The files to modify

```
%Bundle%/
    DependencyInjection/
        Compiler/
            CiconiaPass.php - [new] (optional) If you prefer to manager extensions by tags
    Resources/
        config/
            services.yml    - [update] Register Ciconia to service container
```

### Configure your services.yml

```
services:
    markdown.renderer.html:
        class: Ciconia/Renderer/HtmlRenderer
    markdown.parser:
        class: Ciconia/Ciconia
        arguments: [@markdown.renderer.html]
```

### Using it

``` php
$this->get('markdown.parser')->render($markdown);
```

### Manage extensions using tags

<h2 id="pimple">Pimple/Silex</h2>

### Configure Pimple

``` php
$app['markdown.renderer'] = function () {
    return new HtmlRenderer();
};

$app['ciconia.extensions'] = function () {
    return [
        new Gfm\TableExtension()
    ];
};

$app['markdown.parser'] = function (Pimple $app) {
    $ciconia = new Ciconia($app['markdown.renderer']);
    $ciconia->addExtensions($app['ciconia.extensions']);

    return $ciconia;
});
```

<div class="alert alert-info">
<p>Before Pimle 1.1, you have to define shared object like this:</p>
<pre>$app['markdown.renderer'] = $app->share(function () {
    return new HtmlRenderer();
});</pre>
</div>

### Using it

``` php
$app['markdown.parser']->render($markdown);
```

<h2 id="bowl">Bowl</h2>

### Configure Bowl

``` php
$bowl->share('markdown.renderer', function () {
    return new HtmlRenderer();
});

$bowl->share('markdown.parser', function () {
    $ciconia = new Ciconia($this->get('markdown.renderer'));

    foreach ($this->getTaggedServices('ciconia.extension') as $extension) {
        $ciconia->addExtension($extension);
    }

    return $ciconia;
});

$bowl->share('ciconia.extension.table', function () {
    return new Gfm\TableExtension();
}, ['ciconia.extension']);
```

### Using it

``` php
$bowl->get('markdown.parser')->render($markdown);
```