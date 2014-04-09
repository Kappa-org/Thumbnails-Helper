# Kappa\ThumbnailsHelper

Simple and intelligent system for creating and work with thumbnails

## Requirements:

* PHP 5.3.6 or higher
* [Nette\DI](https://github.com/nette/di) 2.2.*
* [Flame\Module](https://github.com/flame-org/modules) 1.0.*
* [Kappa\FileSystem](https://github.com/Kappa-org/FileSystem)

## Installation

The best way to install Kappa\ThumbnailsHelper is using Composer:
```bash
$ composer require kappa/thumbnails-helper:@dev
```

## Usages

You must register two extension:
```yaml
extensions:
	- Flame\Modules\DI\ModulesExtension
	thumb: Kappa\ThumbnailsHelper\DI\ThumbnailsHelperExtension
```

Into presenter or control where you can use this helper add trait
```php
class HomepagePresenter extends Presenter
{
	use TemplateFactory;
//...
```

and you can use helper in templates
```html
<img src="{$photo|thumb:'100x300':'fit'}">
```
Size can be in next formats:
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
