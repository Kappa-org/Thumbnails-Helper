<?php
/**
 * This file is part of the Kappa\ThumbnailsHelper package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThumbnailsHelper;

use Kappa\FileSystem\File;
use Nette\Utils\Image;
use Kappa\FileSystem\SplFileInfo;

/**
 * Class Thumbnails
 * @package Kappa\ThumbnailsHelper
 */
class Thumbnails
{
	/** @var \Kappa\ThumbnailsHelper\Configurator */
	private $configurator;

	/** @var array */
	private $sizes = array(500, 500);

	/** @var int */
	private $flag = Image::FIT;

	/** @var \Kappa\FileSystem\File */
	private $source;

	/**
	 * @param Configurator $configurator
	 */
	public function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * @param string $sizes
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setSizes($sizes)
	{
		$sizes = explode("x", $sizes);
		if (count($sizes) != 2) {
			throw new InvalidArgumentException("Size '{$sizes}' has not valid format");
		}
		$this->sizes = array_map(array($this, "parseSize"), $sizes);

		return $this;
	}

	/**
	 * @param string $flag
	 * @return $this
	 * @throws InvalidArgumentException
	 */
	public function setFlag($flag)
	{
		$flag = @constant('Nette\Utils\Image::' . strtoupper($flag));
		if ($flag === null) {
			throw new InvalidArgumentException("Unknown flag '{$flag}' you can use only fill, fit, exact, stretch or shrink_only");
		}
		$this->flag = $flag;

		return $this;
	}

	/**
	 * @param string $source
	 * @return $this
	 */
	public function setSource($source)
	{
		$this->source = File::open($source);

		return $this;
	}

	/**
	 * @return SplFileInfo
	 * @throws InvalidArgumentException
	 */
	public function getThumb()
	{
		if (!$this->source instanceof File) {
			throw new InvalidArgumentException("Missing source");
		}
		$thumbName = $this->getThumbnailName();
		$thumbnail = $this->configurator->getThumbDir()->getInfo()->getPathname() . DIRECTORY_SEPARATOR . $thumbName;
		if (is_file($thumbnail)) {
			return new SplFileInfo($thumbnail);
		} else {
			$image = $this->source->toImage();
			if (!$this->configurator->getSizeUp() && $image->width <= $this->sizes[0] && $image->getHeight() <= $this->sizes[1]) {
				return $this->source->getInfo();
			} else {
				$image->resize($this->sizes[0], $this->sizes[1]);
				$file = File::fromImage($image, $thumbnail);

				return $file->getInfo();
			}
		}
	}

	/**
	 * @return string
	 */
	private function getThumbnailName()
	{
		$info = $this->source->getInfo();

		return md5($info->getPathname() . $info->getSize() . $info->getMTime() . $this->sizes[0] . $this->sizes[1] . $this->flag) . '.' . $info->getExtension();
	}

	/**
	 * @param string $size
	 * @return int|null
	 */
	private function parseSize($size)
	{
		if (!$size) {
			return null;
		} else {
			return (int)$size;
		}
	}
} 