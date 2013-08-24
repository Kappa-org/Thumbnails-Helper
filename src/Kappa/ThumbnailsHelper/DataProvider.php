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

use Kappa\FileSystem\Directory;

/**
 * Class DataProvider
 * @package Kappa\ThumbnailsHelper
 */
class DataProvider implements IDataProvider
{
	/** @var \Kappa\FileSystem\Directory */
	private $wwwDir;

	/** @var \Kappa\FileSystem\Directory */
	private $thumbDir;

	/** @var null|int|string */
	private $frequency;

	/**
	 * Set path to thumb directory
	 *
	 * @param string $thumbDir
	 * @throws DirectoryNotFoundException
	 */
	public function setThumbDir($thumbDir)
	{
		$this->thumbDir = new Directory($thumbDir);
		if (!$this->thumbDir->isUsable()) {
			throw new DirectoryNotFoundException("Directory {$thumbDir} has not been found");
		}
	}

	/**
	 * Set control frequency
	 *
	 * @param int|string $frequency
	 * @throws InvalidArgumentException
	 */
	public function setFrequency($frequency = null)
	{
		if ($frequency !== null && (!is_numeric($frequency) && !is_string($frequency))) {
			throw new InvalidArgumentException("Control frequency must be integer or string, " . gettype($frequency) . " given");
		}
		$this->frequency = $frequency;
	}

	/**
	 * @param string $wwwDir
	 * @throws DirectoryNotFoundException
	 */
	public function setWwwDir($wwwDir)
	{
		$this->wwwDir = new Directory($wwwDir);
		if (!$this->wwwDir->isUsable()) {
			throw new DirectoryNotFoundException("Directory {$wwwDir} has not been found");
		}
	}

	/**
	 * @return Directory
	 */
	public function getWwwDir()
	{
		return $this->wwwDir;
	}

	/**
	 * @return Directory
	 */
	public function getThumbDir()
	{
		return $this->thumbDir;
	}

	/**
	 * @return int|null|string
	 */
	public function getFrequency()
	{
		return $this->frequency;
	}
}