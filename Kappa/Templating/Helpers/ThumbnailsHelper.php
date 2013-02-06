<?php
/**
 * ThumbnailsHelper.php
 * Autgor: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 20.12.12
 */

namespace Kappa\Templating\Helpers;

use Kappa\Utils\Validators;
use Kappa\Utils\FileSystem\Files;
use Kappa\Utils\FileSystem\Directories;
use Nette\Image;

class ThumbnailsHelper extends \Nette\Object
{
	/** @var array */
	private $params;

	/** @var array */
	private $sizes;

	/** @var string */
	private $flags;

	/** @var string */
	private $originalImage;

	/** @var string */
	private $thumbImage;



	/**
	 * @param $wwwDir
	 * @param $thumbDir
	 * @param null $frequencyControl
	 */
	public function __construct($wwwDir, $thumbDir, $frequencyControl = null)
	{
		$this->params['wwwDir'] = $wwwDir;
		$this->params['thumbDir'] = $thumbDir;
		$this->params['frequencyControl'] = $frequencyControl;
	}



	/**
	 * @param string $src
	 * @param array $sizes
	 * @param string $flag
	 * @return string
	 * @throws \Kappa\Exceptions\LogicException\InvalidArgumentException
	 */
	public function thumb($src, $sizes = array(300, 200), $flag = "FIT")
	{
		$this->sizes = $sizes;
		if (defined('\Nette\Image::' . $flag)) {
			$flag = constant('\Nette\Image::' . $flag);
		} else {
			throw new InvalidArgumentException("Flag '$flag' not found. Please select only between FIT, FILL, EXACT, SHRINK_ONLY, STRETCH flags");
		}
		$this->prepare($src);
		if (!file_exists($this->originalImage) || !Validators::isImage($this->originalImage)) {
			throw new InvalidArgumentException('Helper thumb must be used only for image files. "' . $src . '"');
		}
		if (file_exists($this->thumbImage)) {
			return $this->getRelativePath($this->thumbImage);
		} else {
			$imgInfo = getimagesize($this->originalImage);
			if ($imgInfo[0] <= $this->sizes[0] || $imgInfo[1] <= $this->sizes[1]) {
				return $this->getRelativePath($this->originalImage);
			} else {
				$this->createThumb();
				return $this->getRelativePath($this->thumbImage);
			}
		}
	}



	/**
	 * @param $relativePath
	 */
	private function prepare($relativePath)
	{
		$this->originalImage = $this->params['wwwDir'] . $relativePath;
		$this->thumbImage = $this->createThumbName();
		$this->checkThumbDir();
		if ($this->params['frequencyControl'] !== null) {
			$this->deleteOlderThumbnails();
		}
	}



	/**
	 * @return string
	 */
	private function createThumbName()
	{
		$path = $this->params['wwwDir'];
		$path .= $this->params['thumbDir'];
		$path .= (string)strrchr($this->originalImage, "/");
		$type = (string)strrchr($this->originalImage, ".");

		$newName = "_thumb";
		$newName .= $this->sizes[0] . 'x' . $this->sizes[1];
		$newName .= '_' . Files::md5sum($this->originalImage);
		$newName .= '_' . fileatime($this->originalImage);
		$newName .= $type;

		$thumbName = str_replace($type, $newName, $path);
		return $thumbName;
	}



	/**
	 * @param string $path
	 * @return string
	 */
	private function getRelativePath($path)
	{
		$to = strlen($this->params['wwwDir']);
		return substr($path, $to);
	}



	private function createThumb()
	{
		list($width, $height) = $this->sizes;
		$image = Image::fromFile($this->originalImage);
		$image->resize($width, $height, $this->flags);
		$image->save($this->thumbImage);
	}



	private function checkThumbDir()
	{
		$dir = $this->params['wwwDir'];
		$dir .= $this->params['thumbDir'];
		if (!is_dir($dir)) {
			Directories::create($dir, '0777');
		}
	}



	private function deleteOlderThumbnails()
	{
		$path = $this->params['wwwDir'];
		$path .= $this->params['thumbDir'];
		$day = $this->params['frequencyControl'];
		$file = $path . '/check.txt';
		if (file_exists($file)) {
			$lastControl = Files::read($file);
			if ($lastControl <= (strtotime("now") - (60 * 60 * 24 * $day))) {
				$filesForDelete = Files::selectOlderThan($path, $day);
				Files::deleteFiles($filesForDelete);
			}
			Files::rewrite($file, strtotime("now"));
		} else {
			Files::create($file, strtotime("now"));
		}
	}
}