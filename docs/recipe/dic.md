---
title: "Using DI Container to manage extensions - Ciconia (A Markdown Parser"
layout: docs
---

<h2 class="title">Using DI Container to manage extensions</h2>

### Symfony

#### The files to modify

```
%Bundle%/
    DependencyInjection/
        Compiler/
            CiconiaPass.php - [new] (optional) If you prefer to manager extensions by tags
    Resources/
        config/
            services.yml    - [update] Register Ciconia to service container
```

#### Configure your services.yml

```
services:
    markdown.renderer.html:
        class: Ciconia/Renderer/HtmlRenderer
    markdown.parser:
        class: Ciconia/Ciconia
        arguments: [@markdown.renderer.html]
```

Now you can use Ciconia via service container

``` php
$this->get('markdown.parser')->render($markdown);
```

#### Manage extensions using tags



### Pimple

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

Before Pimle 1.1, you have to define shared object like this:

``` php
$app['markdown.renderer'] = $app->share(function () {
    return new HtmlRenderer();
});
```

Using it.

``` php
$app['markdown.parser']->render($markdown);
```

### Bowl

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

Using it

``` php
$bowl->get('markdown.parser')->render($markdown);
```