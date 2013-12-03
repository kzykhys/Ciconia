---
layout: syntax
title: "Markdown Syntax - Ciconia (A Markdown Parser)"
---

<h2 class="title">Markdown Syntax</h2>

### Headings

<div class="panel">
<h4 class="panel-heading">
    H1/H2 (Set-ext style) <small class="label label-info pull-right">Core\HeaderExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">

<p>H1 is underlined using equal signs, and H2 is underlined using dashes.</p>

<ul class="nav nav-tabs">
  <li class="active"><a href="#header12-md" data-toggle="tab">Markdown</a></li>
  <li><a href="#header12-html" data-toggle="tab">HTML</a></li>
</ul>

<div class="tab-content" style="margin-top:10px;">
  <div class="tab-pane active" id="header12-md">
    {% gist 7745918 core-header-setext.md.txt %}
  </div>
  <div class="tab-pane" id="header12-html">
    {% gist 7745918 core-header-setext.html.txt %}
  </div>
</div>

</div>
</div>


<div class="panel">
<h4 class="panel-heading">
    H1~H6 (Atx-style)
    <small class="label label-info pull-right">Core\HeaderExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">

<p>Atx-style headers use 1-6 hash characters at the start of the line.</p>

<ul class="nav nav-tabs">
  <li class="active"><a href="#header16-md" data-toggle="tab">Markdown</a></li>
  <li><a href="#header16-html" data-toggle="tab">HTML</a></li>
</ul>

<div class="tab-content" style="margin-top:10px;">
  <div class="tab-pane active" id="header16-md">
    {% gist 7745918 core-header-atx.md.txt %}
  </div>
  <div class="tab-pane" id="header16-html">
    {% gist 7745918 core-header-atx.html.txt %}
  </div>
</div>

</div>
</div>

### Paragraphs

<div class="panel">
<h4 class="panel-heading">
    Paragraphs
    <small class="label label-info pull-right">Core\ParagraphExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p>A paragraph is simply one or more consecutive lines of text, separated by one or more blank lines.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#paragraph-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#paragraph-html" data-toggle="tab">HTML</a></li>
  </ul>

  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="paragraph-md">
      {% gist 7745918 core-paragraphs.md.txt %}
    </div>
    <div class="tab-pane" id="paragraph-html">
       {% gist 7745918 core-paragraphs.html.txt %}
    </div>
  </div>
</div>
</div>

### Blockquotes

<div class="panel">
<h4 class="panel-heading">
    Blockquotes
    <small class="label label-info pull-right">Core\BlockQuoteExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p>A paragraph is simply one or more consecutive lines of text, separated by one or more blank lines.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#blockquote1-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#blockquote1-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="blockquote1-md">
      {% gist 7745918 core-blockquotes1.md.txt %}
    </div>
    <div class="tab-pane" id="blockquote1-html">
       {% gist 7745918 core-blockquotes1.html.txt %}
    </div>
  </div>

  <p>Blockquotes can be nested.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#blockquote2-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#blockquote2-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="blockquote2-md">
      {% gist 7745918 core-blockquotes2.md.txt %}
    </div>
    <div class="tab-pane" id="blockquote2-html">
       {% gist 7745918 core-blockquotes2.html.txt %}
    </div>
  </div>

  <p>Blockquotes can contain other Markdown elements.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#blockquote3-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#blockquote3-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="blockquote3-md">
      {% gist 7745918 core-blockquotes3.md.txt %}
    </div>
    <div class="tab-pane" id="blockquote3-html">
       {% gist 7745918 core-blockquotes3.html.txt %}
    </div>
  </div>
</div>
</div>

### Lists

<div class="panel">
<h4 class="panel-heading">
    Unordered Lists
    <small class="label label-info pull-right">Core\ListExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p>Start each line with hyphens, asterisks or pluses.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#ul-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#ul-html" data-toggle="tab">HTML</a></li>
  </ul>

  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="ul-md">
      {% gist 7745918 core-unordered-lists.md.txt %}
    </div>
    <div class="tab-pane" id="ul-html">
      {% gist 7745918 core-unordered-lists.html.txt %}
    </div>
  </div>
</div>
</div>

<div class="panel">
<h4 class="panel-heading">
    Ordered Lists
    <small class="label label-info pull-right">Core\ListExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p>Start each line with number and a period.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#ol-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#ol-html" data-toggle="tab">HTML</a></li>
  </ul>

  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="ol-md">
      {% gist 7745918 core-ordered-lists.md.txt %}
    </div>
    <div class="tab-pane" id="ol-html">
      {% gist 7745918 core-ordered-lists.html.txt %}
    </div>
  </div>
</div>
</div>

### Code Blocks / Inline Code

<div class="panel">
<h4 class="panel-heading">
    Code Blocks
    <small class="label label-info pull-right">Core\CodeExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#codeblocks-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#codeblocks-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="codeblocks-md">
      {% gist 7745918 core-code-blocks.md.txt %}
    </div>
    <div class="tab-pane" id="codeblocks-html">
      {% gist 7745918 core-code-blocks.html.txt %}
    </div>
  </div>

  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#codeblocks2-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#codeblocks2-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
      <div class="tab-pane active" id="codeblocks2-md">
        {% gist 7745918 core-code-blocks2.md.txt %}
      </div>
      <div class="tab-pane" id="codeblocks2-html">
        {% gist 7745918 core-code-blocks2.html.txt %}
      </div>
    </div>
</div>
</div>

<div class="panel">
<h4 class="panel-heading">
    Inline Code
    <small class="label label-info pull-right">Core\CodeExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#codespan-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#codespan-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="codespan-md">
      {% gist 7745918 core-inline-code.md.txt %}
    </div>
    <div class="tab-pane" id="codespan-html">
      {% gist 7745918 core-inline-code.html.txt %}
    </div>
  </div>
</div>
</div>

### Horizontal Rules

<div class="panel">
<h4 class="panel-heading">
    Horizontal Rules
    <small class="label label-info pull-right">Core\HorizontalRuleExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#hr-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#hr-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="hr-md">
      {% gist 7745918 core-horizontal-rules.md.txt %}
    </div>
    <div class="tab-pane" id="hr-html">
      {% gist 7745918 core-horizontal-rules.html.txt %}
    </div>
  </div>
</div>
</div>

### Links

<div class="panel">
<h4 class="panel-heading">
    Inline Links
    <small class="label label-info pull-right">Core\LinkExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#links1-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#links1-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="links1-md">
      {% gist 7745918 core-links.md.txt %}
    </div>
    <div class="tab-pane" id="links1-html">
      {% gist 7745918 core-links.html.txt %}
    </div>
  </div>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#links2-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#links2-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="links2-md">
      {% gist 7745918 core-links2.md.txt %}
    </div>
    <div class="tab-pane" id="links2-html">
      {% gist 7745918 core-links2.html.txt %}
    </div>
  </div>
</div>
</div>

<div class="panel">
<h4 class="panel-heading">
    Reference-style Links
    <small class="label label-info pull-right">Core\LinkExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#links3-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#links3-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="links3-md">
      {% gist 7745918 core-links3.md.txt %}
    </div>
    <div class="tab-pane" id="links3-html">
      {% gist 7745918 core-links3.html.txt %}
    </div>
  </div>
</div>
</div>

### Emphasis

<div class="panel">
<h4 class="panel-heading">
    Bold and Italics
    <small class="label label-info pull-right">Core\InlineStyleExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#emphasis-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#emphasis-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="emphasis-md">
      {% gist 7745918 core-emphasis.md.txt %}
    </div>
    <div class="tab-pane" id="emphasis-html">
      {% gist 7745918 core-emphasis.html.txt %}
    </div>
  </div>
</div>
</div>

### Images

<div class="panel">
<h4 class="panel-heading">
    Inline Images
    <small class="label label-info pull-right">Core\ImageExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#image1-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#image1-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="image1-md">
      {% gist 7745918 core-images.md.txt %}
    </div>
    <div class="tab-pane" id="image1-html">
      {% gist 7745918 core-images.html.txt %}
    </div>
  </div>
</div>
</div>

<div class="panel">
<h4 class="panel-heading">
    Reference-style Images
    <small class="label label-info pull-right">Core\ImageExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#image2-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#image2-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="image2-md">
      {% gist 7745918 core-images2.md.txt %}
    </div>
    <div class="tab-pane" id="image2-html">
      {% gist 7745918 core-images2.html.txt %}
    </div>
  </div>
</div>
</div>

<h2 class="title">Github Flavored Markdown Syntax</h2>

<div class="alert alert-danger">
    You have to activate Gfm/* extensions if you prefer to use following syntax.
</div>

### Links

<div class="alert alert-info">
    <p>You have to activate Gfm\UrlAutoLinkExtension to use this syntax.</p>
    <pre>$ciconia->addExtension(new \Ciconia\Extension\Gfm\UrlAutoLinkExtension());</pre>
</div>

<div class="panel">
<h4 class="panel-heading">
    URL Autolinking
    <small class="label label-info pull-right">Gfm\UrlAutoLinkExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">
  <p>GFM will autolink standard URLs, so if you want to link to a URL (instead of setting link text),
     you can simply enter the URL and it will be turned into a link to that URL.</p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-link-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-link-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-link-md">
      {% gist 7745918 gfm-links.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-link-html">
      {% gist 7745918 gfm-links.html.txt %}
    </div>
  </div>
</div>
</div>

### Emphasis

<div class="alert alert-info">
    <p>You have to activate Gfm\InlineStyleExtension to use this syntax.</p>
    <pre>$ciconia->addExtension(new \Ciconia\Extension\Gfm\InlineStyleExtension());</pre>
</div>

<div class="panel">
<h4 class="panel-heading">
    Strikethrough
    <small class="label label-info pull-right">Gfm\InlineStyleExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-strike-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-strike-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-strike-md">
      {% gist 7745918 gfm-strike-through.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-strike-html">
      {% gist 7745918 gfm-strike-through.html.txt %}
    </div>
  </div>
</div>
</div>

### Fenced code blocks

<div class="alert alert-info">
    <p>You have to activate Gfm\FencedCodeBlockExtension to use this syntax.</p>
    <pre>$ciconia->addExtension(new \Ciconia\Extension\Gfm\FencedCodeBlockExtension());</pre>
</div>

<div class="panel">
<h4 class="panel-heading">
    Code Blocks
    <small class="label label-info pull-right">Gfm\FencedCodeBlockExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">
  <p>Just wrap your code blocks in <code>```</code> and you won't need to indent manually to trigger a code block. </p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-codeblocks-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-codeblocks-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-codeblocks-md">
      {% gist 7745918 gfm-fenced-code-blocks.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-codeblocks-html">
      {% gist 7745918 gfm-fenced-code-blocks.html.txt %}
    </div>
  </div>
</div>
</div>

<div class="panel">
<h4 class="panel-heading">
    Syntax Highlighting
    <small class="label label-info pull-right">Gfm\FencedCodeBlockExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">
  <div class="alert alert-info">
    <p>As of 1.0, FencedCodeBlockExtension only supports **google-code-prettify**.
       To get colored output, You have to include the script tag below in your document.</p>
    <pre>&lt;script src="https://google-code-prettify.googlecode.com/svn/loader/run_prettify.js"&gt;&lt;/script&gt;</pre>
  </div>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-syntax-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-syntax-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-syntax-md">
      {% gist 7745918 gfm-fenced-code-blocks2.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-syntax-html">
      {% gist 7745918 gfm-fenced-code-blocks2.html.txt %}
    </div>
  </div>
</div>
</div>

### Task Lists

<div class="alert alert-info">
    <p>You have to activate Gfm\TaskListExtension to use this syntax.</p>
    <pre>$ciconia->addExtension(new \Ciconia\Extension\Gfm\TaskListExtension());</pre>
</div>

<div class="panel">
<h4 class="panel-heading">
    Task Lists
    <small class="label label-info pull-right">Gfm\TaskListExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-tasklist-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-tasklist-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-tasklist-md">
      {% gist 7745918 gfm-task-lists.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-tasklist-html">
      {% gist 7745918 gfm-task-lists.html.txt %}
    </div>
  </div>
</div>
</div>

### Tables

<div class="alert alert-info">
    <p>You have to activate Gfm\TableExtension to use this syntax.</p>
    <pre>$ciconia->addExtension(new \Ciconia\Extension\Gfm\TableExtension());</pre>
</div>

<div class="panel">
<h4 class="panel-heading">
    Tables
    <small class="label label-info pull-right">Gfm\TableExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">
  <p></p>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-table1-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-table1-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-table1-md">
      {% gist 7745918 gfm-tables.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-table1-html">
      {% gist 7745918 gfm-tables.html.txt %}
    </div>
  </div>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-table2-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-table2-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-table2-md">
      {% gist 7745918 gfm-tables2.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-table2-html">
      {% gist 7745918 gfm-tables2.html.txt %}
    </div>
  </div>

  <ul class="nav nav-tabs">
    <li class="active"><a href="#gfm-table3-md" data-toggle="tab">Markdown</a></li>
    <li><a href="#gfm-table3-html" data-toggle="tab">HTML</a></li>
  </ul>
  <div class="tab-content" style="margin-top:10px;">
    <div class="tab-pane active" id="gfm-table3-md">
      {% gist 7745918 gfm-tables3.md.txt %}
    </div>
    <div class="tab-pane" id="gfm-table3-html">
      {% gist 7745918 gfm-tables3.html.txt %}
    </div>
  </div>
</div>
</div>

<p class="text-center" style="margin-top:20px;">
  <a class="btn btn-default" href="/docs/recipes.html">Prev: Recipes</a>&nbsp;
  <a class="btn btn-default" href="development.html">Next: Development</a>
</p>