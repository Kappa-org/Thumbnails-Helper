<?php
/**
 * ThumbnailsHelper.php
 * Autgor: Ondřej Záruba <zarubaondra@gmail.com>
 * Date: 20.12.12
 */

namespace Kappa\ThumbnailsHelper;

use Kappa\Utils\Strings;
use Kappa\Utils\Validators;
use Kappa\Utils\FileSystem\Files;
use Kappa\Utils\FileSystem\Directories;
use Nette\Image;
use Nette\Object;

class ThumbnailsHelper extends Object
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
	 * @param string $wwwDir
	 * @param string $thumbDir
	 * @param null|int $frequencyControl
	 * @throws \Kappa\DirectoryNotFoundException
	 * @throws \Kappa\InvalidArgumentException
	 */
	public function __construct($wwwDir, $thumbDir, $frequencyControl = null)
	{
		if (!is_dir($wwwDir)) {
			throw new \Kappa\DirectoryNotFoundException(__METHOD__, $wwwDir);
		}
		if (!is_int($frequencyControl) && $frequencyControl !== null) {
			throw new \Kappa\InvalidArgumentException("Class " . __METHOD__ . " required as third argument integer");
		}
		$this->params['wwwDir'] = realpath($wwwDir);
		$this->params['thumbDir'] = $this->checkThumbDir($thumbDir);
		$this->params['frequencyControl'] = $frequencyControl;
	}

	/**
	 * @param string $src
	 * @param array $sizes
	 * @param string $flag
	 * @return string
	 * @throws \Kappa\InvalidArgumentException
	 * @throws \Kappa\FileNotFoundException
	 * @throws \Kappa\ImageNotFoundException
	 */
	public function thumb($src, array $sizes = array(300, 200), $flag = "FIT")
	{
		$this->sizes = $sizes;
		if (defined('\Nette\Image::' . $flag)) {
			$this->flags = constant('\Nette\Image::' . $flag);
		} else {
			throw new \Kappa\InvalidArgumentException("Flag '$flag' not found. Please select only between FIT, FILL, EXACT, SHRINK_ONLY, STRETCH flags");
		}
		$this->prepare($src);
		if (!file_exists($this->originalImage)) {
			throw new \Kappa\FileNotFoundException(__METHOD__, $src);
		}
		if (!Validators::isImage($this->originalImage)) {
			throw new \Kappa\ImageNotFoundException(__METHOD__, $src);
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
	 * @param string $relativePath
	 */
	private function prepare($relativePath)
	{
		$this->originalImage = Strings::repairPathSeparators($this->params['wwwDir'] . $relativePath);
		$this->thumbImage = $this->createThumbName();
		if ($this->params['frequencyControl'] !== null) {
			$this->deleteOlderThumbnails();
		}
	}

	/**
	 * @return string
	 */
	private function createThumbName()
	{
		$path = $this->params['thumbDir'];
		$path .= (string)strrchr($this->originalImage, DIRECTORY_SEPARATOR);
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
		$relative = substr($path, $to);
		return $relative;
	}

	private function createThumb()
	{
		list($width, $height) = $this->sizes;
		$image = Image::fromFile($this->originalImage);
		$image->resize($width, $height, $this->flags);
		$image->save($this->thumbImage);
	}

	private function checkThumbDir($thumbDir)
	{
		$dir = $this->params['wwwDir'];
		$dir .= DIRECTORY_SEPARATOR;
		$dir .= $thumbDir;
		if (!is_dir($dir)) {
			Directories::create($dir, '0777');
		}
		return realpath($dir);
	}

	private function deleteOlderThumbnails()
	{
		$path = $this->params['thumbDir'];
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