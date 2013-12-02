---
title: "Using DI Container to manage extensions - Ciconia (A Markdown Parser)"
layout: docs
---

<h2 class="title">Using DI Container to manage extensions</h2>

<p>Ciconia is DIC friendly library. You can work with various DI containers (symfony/dependency-injection, pimple/pimple, kzykhys/bowl...)</p>

<ul style="font-size:18px;line-height: 1.5;">
  <li><a href="#symfony">Symfony</a></li>
  <li><a href="#pimple">Pimple/Silex</a></li>
  <li><a href="#bowl">Bowl</a></li>
</ul>

<h2 id="symfony" class="title">Symfony</h2>

### The files to modify

```
%Bundle%/
    DependencyInjection/
        Compiler/
            CiconiaPass.php - [new] (optional) If you prefer to manager extensions by tags
    Resources/
        config/
            services.yml    - [update] Register Ciconia to service container
    XxxBundle.php           - [update] (optional) If you prefer to manager extensions by tags
```

### Configure your services.yml

``` yml
services:
    markdown.renderer.html:
        class: Ciconia\Renderer\HtmlRenderer
    markdown.parser:
        class: Ciconia\Ciconia
        arguments: [@markdown.renderer.html]
```

### Using it

``` php
$this->get('markdown.parser')->render($markdown);
```

### Manage extensions using tags

You have to add a `CompilerPass` class to handle services tagged as `ciconia.extension`

```
namespace Acme\MarkdownBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CiconiaExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('markdown.parser')) {
            return;
        }

        $definition = $container->getDefinition(
            'markdown.parser'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'ciconia.extension'
        );

        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall('addExtension', array(new Reference($id)));
            }
        }
    }
}
```

Then, modify your `Bundle` class.

``` php
namespace Acme\Bundle\MarkdownBundle;

use Lextype\Bundle\MarkdownBundle\DependencyInjection\Compiler\CiconiaExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeMarkdownBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CiconiaExtensionPass());
    }

}
```

Add a extension to services.yml

``` yml
services:
    markdown.renderer.html:
        class: Ciconia\Renderer\HtmlRenderer
    markdown.parser:
        class: Ciconia\Ciconia
        arguments: [@markdown.renderer.html]
    ciconia.extension.table:
        class: Ciconia\Extension\Gfm\TableExtension
        tags:
            - { name: ciconia.extension }
```

Now, Symfony automatically adds extensions to Ciconia.

``` php
$this->get('markdown.parser')->render($markdown);
```

<h2 id="pimple" class="title">Pimple/Silex</h2>

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

<h2 id="bowl" class="title">Bowl</h2>

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