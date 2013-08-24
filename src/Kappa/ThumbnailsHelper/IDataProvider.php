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

/**
 * Class IDataProvider
 * @package Kappa\ThumbnailsHelper
 */
interface IDataProvider
{
	/**
	 * @param \Kappa\FileSystem\Directory $thumbDir
	 * @return void
	 */
	public function setThumbDir($thumbDir);

	/**
	 * @return \Kappa\FileSystem\Directory
	 */
	public function getThumbDir();

	/**
	 * @param \Kappa\FileSystem\Directory $wwwDir
	 * @return void
	 */
	public function setWwwDir($wwwDir);

	/**
	 * @return \Kappa\FileSystem\Directory
	 */
	public function getWwwDir();

	/**
	 * @param null|int|string $frequency
	 * @return void
	 */
	public function setFrequency($frequency);

	/**
	 * @return int|null|string
	 */
	public function getFrequency();
}