# Gutenberg Packages

[![Build Status](https://travis-ci.com/technote-space/gutenberg-packages.svg?branch=master)](https://travis-ci.com/technote-space/gutenberg-packages)
[![Coverage Status](https://coveralls.io/repos/github/technote-space/gutenberg-packages/badge.svg?branch=master)](https://coveralls.io/github/technote-space/gutenberg-packages?branch=master)
[![CodeFactor](https://www.codefactor.io/repository/github/technote-space/gutenberg-packages/badge)](https://www.codefactor.io/repository/github/technote-space/gutenberg-packages)
[![License: GPL v2+](https://img.shields.io/badge/License-GPL%20v2%2B-blue.svg)](http://www.gnu.org/licenses/gpl-2.0.html)
[![PHP: >=5.6](https://img.shields.io/badge/PHP-%3E%3D5.6-orange.svg)](http://php.net/)
[![WordPress: >=5.0](https://img.shields.io/badge/WordPress-%3E%3D5.0-brightgreen.svg)](https://wordpress.org/)

This repository (`Gutenberg Packages`) manages versions of Gutenberg.  

`Gutenberg Packages` is wrapper of [this library](https://github.com/technote-space/gutenberg-package-versions) (daily update)   
and will update automatically every week.

`Gutenberg Packages` fetch version data from
1. [Library]((https://github.com/technote-space/gutenberg-package-versions)) (weekly update)
2. [API](https://github.com/technote-space/gutenberg-package-versions/tree/gh-pages) (daily update)  
3. [Gutenberg repository](https://github.com/WordPress/gutenberg)

and will be cached for a day.  
(When the state (WP version or Gutenberg plugin state) changes, the cache is cleared).

## Requirement
- \>= PHP 5.6
- \>= WordPress v5.0

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
```

## Dependency
- [Gutenberg Package Versions](https://github.com/technote-space/gutenberg-package-versions)

## Author
[GitHub (Technote)](https://github.com/technote-space)  
[Blog](https://technote.space)
