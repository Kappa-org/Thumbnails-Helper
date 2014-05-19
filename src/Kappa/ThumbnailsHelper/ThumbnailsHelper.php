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
	 * @param Storage $storage
	 */
	public function __construct(Configurator $configurator, Storage $storage)
	{
		$this->configurator = $configurator;
		$storage->invalidateStorage();
	}

	/**
	 * @param string $source
	 * @param string $sizes
	 * @param string $flag
	 * @return \Kappa\FileSystem\SplFileInfo
	 */
	public function process($source, $sizes, $flag = 'fit')
	{
		$source = $this->configurator->getWwwDir()->getInfo()->getPathname() . DIRECTORY_SEPARATOR . $source;
		if (!is_file($source)) {
			return $source;
		}
		$thumb = new Thumbnails($this->configurator);
		$thumb->setSizes($sizes)
			->setFlag($flag)
			->setSource($source);

		return  $thumb->getThumb()->getRelativePath($this->configurator->getWwwDir()->getInfo()->getPathname());
	}
} 