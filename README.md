#Kappa/ThumbnailsHelper [![Build Status](https://travis-ci.org/Kappa-org/Thumbnails-Helper.png?branch=master)](https://travis-ci.org/Kappa-org/Thumbnails-Helper)

Inteligent heper for work with thumbnail in Nette/Latte templates

## Requirements:

* PHP 5.3 or higher
* [Nette Framework](http://nette.org)
* [Kappa/FileSystem](https://github.com/Kappa-app/FileSystem)
* [Kappa/Nette-FileSystem](https://github.com/Kappa-app/Nette-FileSystem)

## Installation

The best way to install Kappa/FileSystem is using Composer:

```bash
$ composer require kappa/thumbnails-helper:@dev
```

## Usages

```html
<img src={$img|thumb:array(100,100):"STRETCH"}>
```