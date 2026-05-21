# Templating Plugin for CakePHP
[![CI](https://github.com/dereuromark/cakephp-templating/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/dereuromark/cakephp-templating/actions/workflows/ci.yml?query=workflow%3ACI+branch%3Amaster)
[![Coverage](https://codecov.io/github/dereuromark/cakephp-templating/graph/badge.svg)](https://app.codecov.io/github/dereuromark/cakephp-templating/tree/master)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%208.2-8892BF.svg)](https://php.net/)
[![Latest Stable Version](https://poser.pugx.org/dereuromark/cakephp-templating/v/stable.svg)](https://packagist.org/packages/dereuromark/cakephp-templating)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%208-brightgreen.svg?style=flat)](https://phpstan.org/)
[![License](https://poser.pugx.org/dereuromark/cakephp-templating/license)](https://packagist.org/packages/dereuromark/cakephp-templating)
[![Total Downloads](https://poser.pugx.org/dereuromark/cakephp-templating/d/total.svg)](https://packagist.org/packages/dereuromark/cakephp-templating)
[![Coding Standards](https://img.shields.io/badge/cs-PSR--2--R-purple.svg?style=flat-square)](https://github.com/php-fig-rectified/fig-rectified-standards)

A CakePHP plugin to
- make working with HTML and helpers more convenient
- provide (font) icons from various libraries out of the box
- together with IdeHelper also provide auto-complete on those icons

You can use one or many of the following icon sets out of the box:
- [Bootstrap](https://icons.getbootstrap.com/)
- [FontAwesome](https://fontawesome.com/icons) v4/v5/v6/v7
- [Material](https://fonts.google.com/icons)
- [Feather](https://feathericons.com/)
- [Lucide](https://lucide.dev/) (modern Feather fork with 1000+ icons)
- [Heroicons](https://heroicons.com/) (by Tailwind CSS team)

You can also add your own custom icon set.

This branch is for **CakePHP 5.1+**. See [version map](https://github.com/dereuromark/cakephp-templating/wiki#cakephp-version-map) for details.

## Setup
```
composer require dereuromark/cakephp-templating
```
and
```
bin/cake plugin load Templating
```

## Documentation

Full documentation lives at **[dereuromark.github.io/cakephp-templating](https://dereuromark.github.io/cakephp-templating/)**.

A few good entry points:

* [Getting started](https://dereuromark.github.io/cakephp-templating/guide/)
* [HtmlStringable](https://dereuromark.github.io/cakephp-templating/helpers/html-stringable)
* [Icon helper](https://dereuromark.github.io/cakephp-templating/helpers/icon)
* [IconSnippet helper](https://dereuromark.github.io/cakephp-templating/helpers/icon-snippet)
* [Html and Form helpers](https://dereuromark.github.io/cakephp-templating/helpers/html)

The Markdown sources live in the [docs](docs/) directory of this repository.

## Demo
See [sandbox.dereuromark.de/sandbox/templating-examples](https://sandbox.dereuromark.de/sandbox/templating-examples).

## TODO
- Add more useful things - help welcome
