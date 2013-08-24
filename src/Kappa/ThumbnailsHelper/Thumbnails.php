<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThumbnailsHelper;

use Kappa\FileSystem\File;
use Kappa\NetteFileSystem\Image;
use Nette\Object;

/**
 * Class ThumbnailsHelper
 * @package Kappa\ThumbnailsHelper
 */
class Thumbnails extends Object
{
	/** @var \Kappa\ThumbnailsHelper\IManager */
	private $manager;

	/** @var string */
	private $wwwDir;

	/**
	 * @param IManager $manager
	 */
	public function __construct(IManager $manager)
	{
		$this->manager = $manager;
		$this->manager->check();
	}

	/**
	 * @param string $wwwDir
	 * @throws DirectoryNotFoundException
	 */
	public function setWwwDir($wwwDir)
	{
		if (!is_dir($wwwDir)) {
			throw new DirectoryNotFoundException("Directory {$wwwDir} has not been found");
		}
		$this->wwwDir = $wwwDir;
	}

	/**
	 * @param string $original
	 * @param array $sizes
	 * @param string $flag
	 * @return string
	 */
	public function process($original, array $sizes = array(300, 150), $flag = "fit")
	{
		$original = new File($this->wwwDir . DIRECTORY_SEPARATOR . $original);
		$thumb = $this->createThumbName($original, $sizes);
		$imageInfo = @getimagesize($original->getInfo()->getPathname());
		if (file_exists($thumb)) {
			$file = new File($thumb);

			return $file->getInfo()->getRelativePath($this->wwwDir);
		} elseif ($imageInfo[0] <= $sizes[0] && $imageInfo[1] <= $sizes[1]) {
			return $original->getInfo()->getRelativePath($this->wwwDir);
		} else {
			$image = Image::fromFile($original->getInfo()->getPathname());
			$image->resize(300, 100, $this->getFlag($flag));
			$file = $image->save($thumb);

			return $file->getInfo()->getRelativePath($this->wwwDir);
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
	 * @param string $flag
	 * @return int
	 * @throws InvalidArgumentException
	 */
	private function getFlag($flag)
	{
		$flags = array(
			'exact' => \Nette\Image::EXACT,
			'fill' => \Nette\Image::FILL,
			'fit' => \Nette\Image::FIT,
			'shrink_only' => \Nette\Image::SHRINK_ONLY,
			'stretch' => \Nette\Image::STRETCH,
		);
		if (!array_key_exists($flag, $flags)) {
			throw new InvalidArgumentException("Unknown flag {$flag}");
		} else {
			return $flags[$flag];
		}
	}
}