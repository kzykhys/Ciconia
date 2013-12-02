---
layout: syntax
title: "Markdown Syntax - Ciconia (A Markdown Parser)"
---

<h2 class="title">Markdown Syntax</h2>

### Headings

<div class="panel">
<h4 class="panel-heading">
    H1/H2 (Set-ext style) <small class="label label-info pull-right">HeaderExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>
<div class="panel-body">

<p>H1 is underlined using equal signs, and H2 is underlined using dashes.</p>

<ul class="nav nav-tabs">
  <li class="active"><a href="#header12-md" data-toggle="tab">Markdown</a></li>
  <li><a href="#header12-html" data-toggle="tab">HTML</a></li>
</ul>

<div class="tab-content" style="margin-top:10px;">
  <div class="tab-pane active" id="header12-md">
     {% gist 7745918 header-setext.md.txt %}
  </div>
  <div class="tab-pane" id="header12-html">
     {% gist 7745918 header-setext.html.txt %}
  </div>
</div>

</div>
</div>


<div class="panel">
<h4 class="panel-heading">
    H1~H6 (Atx-style)
    <small class="label label-info pull-right">HeaderExtension</small> <small class="label label-info pull-right">1.0</small>
</h4>

<div class="panel-body">

<p>Atx-style headers use 1-6 hash characters at the start of the line.</p>

<ul class="nav nav-tabs">
  <li class="active"><a href="#header16-md" data-toggle="tab">Markdown</a></li>
  <li><a href="#header16-html" data-toggle="tab">HTML</a></li>
</ul>

<div class="tab-content" style="margin-top:10px;">
  <div class="tab-pane active" id="header16-md">
    {% gist 7745918 header-atx.md.txt %}
  </div>
  <div class="tab-pane" id="header16-html">
     {% gist 7745918 header-atx.html.txt %}
  </div>
</div>

</div>
</div>

### Paragraphs

<h2 class="title">Github Flavored Markdown Syntax</h2>

<div class="alert alert-danger">
    You have to activate Gfm/* extensions if you prefer to use following syntax.
</div>

<p class="text-center" style="margin-top:20px;">
  <a class="btn btn-default" href="/docs/recipes.html">Prev: Recipes</a>&nbsp;
  <a class="btn btn-default" href="development.html">Next: Development</a>
</p>