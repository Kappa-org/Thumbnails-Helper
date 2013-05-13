<?php
/**
 * ThumbnailsHelper.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 20.12.12
 *
 * @package Kappa\ThumbnailsHelper
 */

namespace Kappa\ThumbnailsHelper;

use Kappa\FileSystem\Directory;
use Kappa\FileSystem\File;
use Kappa\FileSystem\Image;
use Nette\Object;

/**
 * Class ThumbnailsHelper
 *
 * @package Kappa\ThumbnailsHelper
 */
class ThumbnailsHelper extends Object
{
	/** @var array */
	private $params;

	/**
	 * @param string $wwwDir
	 * @param string $thumbDir
	 * @param null|int $frequencyControl
	 * @throws DirectoryNotFoundException
	 */
	public function __construct($wwwDir, $thumbDir, $frequencyControl = null)
	{
		if (!is_dir($wwwDir)) {
			throw new DirectoryNotFoundException("Directory '$wwwDir' has not been found");
		}
		if (!is_dir($thumbDir)) {
			throw new DirectoryNotFoundException("Directory '$thumbDir' has not been found");
		}
		$this->params = array(
			'wwwDir' => realpath($wwwDir),
			'thumbDir' => realpath($thumbDir),
		);
		if ($frequencyControl) {
			$this->checkThumbs((int)$frequencyControl);
		}
	}

	/**
	 * @param string $original
	 * @param array $sizes
	 * @param string $flag
	 * @return string
	 */
	public function process($original, array $sizes = array(300, 100), $flag = "fit")
	{
		$original = new File($this->params['wwwDir'] . DIRECTORY_SEPARATOR . $original);
		$thumb = $this->createThumbName($original, $sizes);
		$imageInfo = @getimagesize($original->getInfo()->getPathname());
		if (file_exists($thumb) || ($imageInfo[0] <= $sizes[0] && $imageInfo[1] <= $sizes[1])) {
			$file = new File($thumb);
			return $file->getInfo()->getRelativePath($this->params['wwwDir']);
		} else {
			$file = new Image($original->getInfo()->getPathname(), $thumb, $sizes, $flag);
			return $file->getInfo()->getRelativePath($this->params['wwwDir']);
		}
	}

	/**
	 * @param File $original
	 * @param array $sizes
	 * @return mixed
	 */
	private function createThumbName(File $original, array $sizes)
	{

		$path = $this->params['thumbDir'];
		$path .= '/' . $original->getInfo()->getBasename();

		$newName = "_thumb";
		$newName .= $sizes[0] . 'x' . $sizes[1];
		$newName .= '_' . $original->getHash();
		$newName .= '_' . $original->getInfo()->getMTime();
		$newName .= $original->getInfo()->getFileExtension();

		$thumbName = str_replace($original->getInfo()->getFileExtension(), $newName, $path);

		return $thumbName;
	}

	/**
	 * @param int $day
	 */
	private function checkThumbs($day)
	{
		$controlFile = new File($this->params['thumbDir'] . '/lastControl.txt');
		$lastControl = (int)$controlFile->read();
		$time = strtotime("now") - (60 * 60 * 24 * $day);
		if ($lastControl <= $time) {
			$directory = new Directory($this->params['thumbDir']);
			$files = $directory->getFiles();
			foreach ($files as $path => $file) {
				if ($file->getMTime() <= $time) {
					$_file = new File($path);
					$_file->remove();
				}
			}
		}
		$controlFile->overwrite(strtotime('now'));
	}
}