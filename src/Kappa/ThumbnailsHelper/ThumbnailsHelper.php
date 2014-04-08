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
use Kappa\FileSystem\Image;

/**
 * Class ThumbnailsHelper
 * @package Kappa\ThumbnailsHelper
 */
class ThumbnailsHelper
{
	/** @var \Kappa\ThumbnailsHelper\Configurator */
	private $configurator;

	/**
	 * @param Configurator $configurator
	 */
	public function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	/**
	 * @param string $source
	 * @param string $sizes
	 * @param string $flag
	 * @return \Kappa\FileSystem\SplFileInfo
	 */
	public function process($source, $sizes, $flag)
	{
		$thumb = new Thumbnails($this->configurator);
		$thumb->setSizes($sizes)
			->setFlag($flag)
			->setSource($source);

		return  $thumb->getThumb();
	}
} 