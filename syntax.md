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

#### H1~H6 (Atx-style)

Atx-style headers use 1-6 hash characters at the start of the line.

<ul class="nav nav-tabs">
  <li class="active"><a href="#header16-md" data-toggle="tab">Markdown</a></li>
  <li><a href="#header16-html" data-toggle="tab">HTML</a></li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="header16-md">
    <pre style="margin-top:10px;"># Header 1
# Header 2
# Header 3
# Header 4
# Header 5
# Header 6</pre>
  </div>
  <div class="tab-pane" id="header16-html">
     <h1>Header 1</h1>
     <h2>Header 2</h2>
  </div>
</div>