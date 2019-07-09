# Gutenberg Packages

[![Build Status](https://travis-ci.com/technote-space/gutenberg-packages.svg?branch=master)](https://travis-ci.com/technote-space/gutenberg-packages)
[![Coverage Status](https://coveralls.io/repos/github/technote-space/gutenberg-packages/badge.svg?branch=master)](https://coveralls.io/github/technote-space/gutenberg-packages?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/gutenberg-packages/badge)](https://www.codefactor.io/repository/github/technote-space/gutenberg-packages)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![PHP: >=5.6](https://img.shields.io/badge/PHP-%3E%3D5.6-orange.svg)](http://php.net/)
[![WordPress: >=5.0](https://img.shields.io/badge/WordPress-%3E%3D5.0-brightgreen.svg)](https://wordpress.org/)

This repository (`Gutenberg Packages`) manages versions of Gutenberg.  

`Gutenberg Packages` is wrapper of [this library](https://github.com/technote-space/gutenberg-package-versions) (daily update)   
and update package automatically every week.

`Gutenberg Packages` fetches version data from
1. [Library]((https://github.com/technote-space/gutenberg-package-versions)) (weekly update)
2. [API](https://github.com/technote-space/gutenberg-package-versions/tree/gh-pages) (daily update)  
3. [Gutenberg repository](https://github.com/WordPress/gutenberg)

and cache for a day.  
(When the state (WP Core version or Gutenberg plugin state) changes, the cache is cleared).

## Requirement
- \>= PHP 5.6
- \>= WordPress v5.0

## Install
```bash
composer require technote/gutenberg-packages
```

## Usage
```php
<?php
use Technote\GutenbergPackages;

$packages = new GutenbergPackages();

$packages->is_block_editor(); // true or false
$packages->get_gutenberg_version(); // e.g. 6.0.0 (empty string if Gutenberg plugin is not activated)

$packages->get_editor_package_versions(); // array of (package => version), false if block editor is invalid
/** e.g.
[
  "wp-a11y"        => "2.0.2",
  "wp-annotations" => "1.0.8",
  "wp-api-fetch"   => "2.2.8",

  ...

  "wp-url"         => "2.3.3",
  "wp-viewport"    => "2.1.1",
  "wp-wordcount"   => "2.0.3"
]
*/

$packages->get_editor_package_version( 'wp-editor' ); // e.g. 9.0.11
$packages->get_editor_package_version( 'editor' ); // same as above

$packages->is_support_editor_package( 'wp-editor' ); // true or false
$packages->is_support_editor_package( 'editor' ); // same as above

$packages->filter_packages( [
	'editor',
	'wp-editor',
	'test-package',
	'components',
	'wp-data',
	'wp-data',
] );
/** e.g.
[
	'wp-editor',
	'wp-components',
	'wp-data',
]
*/

$packages->fill_package_versions( [
	'editor',
	'wp-editor',
	'test-package',
	'components',
	'wp-data',
	'wp-data',
] );
/** e.g.
[
	'wp-editor'     => '9.0.11',
	'wp-components' => '7.0.8',
	'wp-data'       => '4.2.1',
]
*/
```

## Motivation
There is no WP Core function to get version of Block Editor or its packages.  
So it is hard to consider compatibility.  

For example  
Gutenberg v5.9 outputs message bellow.
```
wp.editor.BlockFormatControls is deprecated and will be removed. Please use wp.blockEditor.BlockFormatControls instead.
```
If your plugin uses `wp-block-editor` package, you get an error under WP v5.2.
```js
const { BlockFormatControls } = wp.blockEditor;
```
```
Uncaught TypeError: Cannot destructure property `BlockFormatControls` of 'undefined' or 'null'.
```
From js, to check existence of property can solve this problem easily.
```js
const { BlockFormatControls } = wp.blockEditor && wp.blockEditor.BlockEdit ? wp.blockEditor : wp.editor;
```

But you also need to know package validity from PHP because `wp_enqueue_script` needs dependencies.  
If you pass `wp-block-editor` to `wp_enqueue_script` under WP v5.2, script is not enqueued.
```php
<?php
wp_enqueue_script( 'test-script', 'path/to/javascript/index.js', [
	'wp-block-editor',
	'wp-components',
	'wp-compose',
	'wp-element',
	'wp-editor',
] );
```

This library can help this problems.
```php
<?php
use Technote\GutenbergPackages;

$packages = new GutenbergPackages();
wp_enqueue_script( 'test-script', 'path/to/javascript/index.js', $packages->filter_packages( [
	'wp-block-editor',
	'wp-components',
	'wp-compose',
	'wp-element',
	'wp-editor',
] ) );
```
If you use under WP v5.1, `wp-block-editor` is filtered.  
And if you use over WP v5.2, `wp-block-editor` is not filtered.
## Dependency
- [Gutenberg Package Versions](https://github.com/technote-space/gutenberg-package-versions)

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)
