# Gutenberg Packages

[![CI Status](https://github.com/technote-space/gutenberg-packages/workflows/CI/badge.svg)](https://github.com/technote-space/gutenberg-packages/actions)
[![codecov](https://codecov.io/gh/technote-space/gutenberg-packages/branch/master/graph/badge.svg)](https://codecov.io/gh/technote-space/gutenberg-packages)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/gutenberg-packages/badge)](https://www.codefactor.io/repository/github/technote-space/gutenberg-packages)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![PHP: >=5.6](https://img.shields.io/badge/PHP-%3E%3D5.6-orange.svg)](http://php.net/)
[![WordPress: >=5.3](https://img.shields.io/badge/WordPress-%3E%3D5.3-brightgreen.svg)](https://wordpress.org/)

This repository (`Gutenberg Packages`) manages versions of Gutenberg.  

`Gutenberg Packages` is wrapper of [this library](https://github.com/technote-space/gutenberg-package-versions).

`Gutenberg Packages` fetches version data from
1. [Library]((https://github.com/technote-space/gutenberg-package-versions))
2. [API](https://github.com/technote-space/gutenberg-package-versions/tree/gh-pages#api) (daily update)  
3. [Gutenberg repository](https://github.com/WordPress/gutenberg)

and cache for a day.  
(When the state (WP Core version or Gutenberg plugin state) changes, the cache is cleared).

## Table of Contents

<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
<details>
<summary>Details</summary>

- [Requirement](#requirement)
- [Installation](#installation)
- [Usage](#usage)
- [Motivation](#motivation)
- [Dependency](#dependency)
- [Author](#author)

</details>
<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Requirement
- \>= PHP 5.6
- \>= WordPress v5.3

## Installation
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
There is no WP Core function to get version of Block Editor packages.  
So it is hard to consider compatibility.  

For example  
Gutenberg v5.9 outputs message bellow.
```
wp.editor.BlockFormatControls is deprecated and will be removed. Please use wp.blockEditor.BlockFormatControls instead.
```
If your plugin uses `wp-block-editor` package like bellow, you get an error under WP v5.2.
```js
const { BlockFormatControls } = wp.blockEditor;
```
```
Uncaught TypeError: Cannot destructure property `BlockFormatControls` of 'undefined' or 'null'.
```
From JavaScript, to check existence of property can solve this problem easily.
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
	'lodash',
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
], [ 'lodash' ] ) );
```
If you use under WP v5.1, `wp-block-editor` is filtered.  
And if you use over WP v5.2, `wp-block-editor` is not filtered.

You can also pass the package versions to JavaScript via `wp_localize_script`.  
```php
<?php
use Technote\GutenbergPackages;

$packages = new GutenbergPackages();

$depends = [
	'wp-block-editor',
	'wp-components',
	'wp-compose',
	'wp-data',
	'wp-element',
	'wp-editor',
];
wp_enqueue_script( 'test-script', 'path/to/javascript/index.js', $packages->filter_packages( $depends, [ 'lodash' ] ) );
wp_localize_script( 'test-script', 'PackageVersions', $packages->fill_package_versions( $depends) );
```
```js
// JavaScript
console.log( PackageVersions );
```
## Dependency
- [Gutenberg Package Versions](https://github.com/technote-space/gutenberg-package-versions)

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)
