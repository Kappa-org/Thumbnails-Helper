# Kappa\ThumbnailsHelper

Simple and intelligent system for creating and work with thumbnails

## Requirements:

* PHP 5.3.6 or higher
* [Nette](https://github.com/nette/nette) ~2.1 or higher
* [Kappa\FileSystem](https://github.com/Kappa-org/FileSystem) 4.1.1 or higher

## Installation

The best way to install Kappa\ThumbnailsHelper is using Composer:
```bash
$ composer require kappa/thumbnails-helper:@dev
```

## Usages

You must register extension:
```yaml
extensions:
	thumb: Kappa\ThumbnailsHelper\DI\ThumbnailsHelperExtension
```

Into presenter or control where you can use this helper add filter (helper)
```php
$template->addFilter('thumb', array($this->thumbnailsHelper, 'process')) // for Nette 2.2
$template->registerHelper('thumb', array($this->thumbnailsHelper, 'process')) // for Nette 2.1
```
**Method in callback must be ```process()```!**

and you can use helper in templates
```html
<img src="{$photo|thumb:'100x300':'fit'}">
```
Size can be in next formats:
* ```NULL``` - Size of photo will be the same as original picture, no resized
* ```100x``` - Width will be 100px and height will be automatically calculated
* ```x100``` - Width will be automatically calculated and height will be 100px
* ```100x100``` - Size will be 100pxx100px

Third argument is resize type, for more info see [documentation](http://doc.nette.org/cs/2.1/images#toc-zmena-velikosti)
 *in czech only*


Helper can be configure in config:
* ```thumbDir:``` - set path to thumb dir **with %wwwDir%** (example ```%wwwDir%/thumbs```)
* ```sizeUp``` - you can set to true or false when you want small image resize to big image
* ```controlFrequency``` - you can set count of days for invalidate storage (remove all thumbnails and create a new only
from usages images). If you set this option on ```false``` or remove option automatically control has been disabled
