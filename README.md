#Kappa:Thumbnails helper

Simple and intelligent system for creating and work with thumbnails

###Requirements:
- PHP 5.3.*
- [Nette framework](http://nette.org/) 2.0.*
- [Kappa:Framework](https://github.com/Kappa-org/Framework)

###Install

1. Step - Add this package into your project
```json
	"require":{
		"kappa/thumbnails-helper" : "dev-master"
	},
```

2. Step - Registre this package in config
<pre>
	nette:
		template:
			helperLoaders: \Kappa\Templating\Helpers
			helpers:
				thumbnails: @Thumbnails::thumb
	services:
		Thumbnails: Kappa\Templating\Helpers\ThumbnailsHelper(%wwwDir%,%imageStorage.thumbDir%)
</pre>

**3. Step - Clean temp directory!**

Complete! :)

###Work with Thumbnails helper
```php
// Presenter
class HomepagePresenter extends \Kappa\Application\UI\Presenter
{
	public function renderDefault()
	{
		$this->template->img = "/media/upload/img.png";
	}
}
```
```html
// Layout
<img src={$img|thumbnails:array(100,100}:"STRETCH">
```
First parameter are sizes thumbnails and second parameter is method resizing [see documentation](http://doc.nette.org/cs/images#toc-zmena-velikosti)
