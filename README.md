# SweetView
SweetView is a lightweight and powerful template engine that unblocks all the most common features in handleing a View in PHP. The template Syntax is you already know.

## Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install SweetView.

```bash
$ composer require php-script/sweet-view
```

## Basic Usage
After Installing SweetView require compoer autoloader then you can simple use it calling view() function

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

echo view('welcome', ['message' => 'Welcome to SweetView']);

// ...
```

Now, Create a View Template and Layout file into: /resources/views/
```php
<?php
// view: welcome.php

$this->layout('layout.master')
  ->block('title', 'Welcome to SweetView');

?>

<h1><?= $message ?></h1>

```
Create a Layout to: /resources/views/layout/
```php
<?php
// layout: master.php

$this->minified(true);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->getBlock('title', 'Welcome Home') ?></title>
</head>
<body>
    <main class="container">
        {{content}}
    </main>
</body>
</html>
```

Thats it

## SweetView template inside available methods
- layout(string $path): self
- block(string $path, $value): self
- with(array $params = []): self
- minified(bool $default = true): self
- include(string $path, array $params = []): self
- require(string $path, array $params = []): self
- includeOnce(string $path, array $params = []): self
- requireOnce(string $path, array $params = []): self
- component(string $path, array $params = []): self

```php
<?php
// view: blogs.php
$this
  // call a layout
  ->layout('layout.master')

  // add a new block
  ->block('title', 'Blogs: SweetView')

  // declare variable all over the view
  ->with(['theme' => 'dark', 'sidebar' => true])

  // require template part
  ->require('layout.breadcrumb')

  // include template part
  ->include('layout.hero')

?>

<!-- include_once template part -->
<?php $this->includeOnce('layout.cta')?>

<div class="row">
  <?php foreach($blogs as $blog): ?>
    <div class="col-md-4">
      <!-- include template component -->
      <?php $this->component('components.blog', ['blog' => $blog]) ?>
    </div>
  <?php endforeach ?>
</div>

<!-- require_once template part -->
<?php $this->requireOnce('includes.contact')?>

```

## SweetView Advanced Usage
```php
<?php
// controller: home.php

use PhpScript\SweetView\Drivers\HtmlDriver;
use PhpScript\SweetView\SweetView;

// create a new SweetView instance
$view = SweetView::create(new HtmlDriver);

// get view engine
$engine = $view->getDriver()->getEngine();

// change resource directory
$engine->resourceDir(__DIR__ . '/resources/views/');

// render output
echo $engine->render('welcome', ['message' => 'Welcome to SweetView']);

// ..

```

## SweetView Html Engine Advanced Usage
```php
<?php
// controller: home.php

use PhpScript\SweetView\Html;

// create a Html instance
$html = Html::load('welcome', 'master');
// Note: layout is optional

// or specify different folder
$html = Html::load(
  __DIR__ . '/resources/views/welcome.php',
  __DIR__ . '/resources/views/layout/master.php'
);

// change resource dir
$html->resourceDir(__DIR__ . '/directory/path/');

// set view file extension
$html->extension('.sweet');

// clean prevouse output
$html->clean();

// enable minification output
$html->minified();

// set global variable in view
$html->with(['theme' => 'dark', 'sidebar' => true]);

// render html output
echo $html->render(['title' => 'SweetView']);

// ..

```