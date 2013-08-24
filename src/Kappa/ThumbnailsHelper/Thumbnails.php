<?php
/**
 * This file is part of the Kappa/ThumbnailsHelper package.
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
	/** @var \Kappa\ThumbnailsHelper\DataProvider */
	private $dataProvider;

	/**
	 * @param DataProvider $dataProvider
	 * @param IManager $manager
	 */
	public function __construct(DataProvider $dataProvider, IManager $manager)
	{
		$this->dataProvider = $dataProvider;
		$manager->check();
	}


	/**
	 * @param string $original
	 * @param array $sizes
	 * @param string $flag
	 * @return string
	 */
	public function process($original, array $sizes = array(300, 150), $flag = "fit")
	{
		$original = new File($this->dataProvider->getWwwDir()->getInfo()->getPathname() . DIRECTORY_SEPARATOR . $original);
		$thumb = $this->createThumbName($original, $sizes);
		$imageInfo = @getimagesize($original->getInfo()->getPathname());
		if (file_exists($thumb)) {
			$file = new File($thumb);

			return $file->getInfo()->getRelativePath($this->dataProvider->getWwwDir()->getInfo()->getPathname());
		} elseif ($imageInfo[0] <= $sizes[0] && $imageInfo[1] <= $sizes[1]) {
			return $original->getInfo()->getRelativePath($this->dataProvider->getWwwDir()->getInfo()->getPathname());
		} else {
			$image = Image::fromFile($original->getInfo()->getPathname());
			$image->resize($sizes[0], $sizes[1], $this->getFlag($flag));
			$file = $image->save($thumb);

			return $file->getInfo()->getRelativePath($this->dataProvider->getWwwDir()->getInfo()->getPathname());
		}
	}

	/**
	 * @param File $original
	 * @param array $sizes
	 * @return mixed
	 */
	private function createThumbName(File $original, array $sizes)
	{

		$path = $this->dataProvider->getThumbDir()->getInfo()->getPathname();
		$path .= '/' . $original->getInfo()->getBasename();

		$newName = "_thumb";
		$newName .= $sizes[0] . 'x' . $sizes[1];
		$newName .= '_' . md5($original->getInfo()->getPathname());
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