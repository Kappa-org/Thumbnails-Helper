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
 * Class Configurator
 * @package Kappa
 */
class Configurator 
{
	/** @var string */
	private $thumbDir;

	/** @var string */
	private $wwwDir;

	/** @var double */
	private $controlFrequency;

	/**
	 * @param string $path
	 * @return $this
	 * @throws DirectoryNotFoundException
	 */
	public function setThumbDir($path)
	{
		if (!is_dir($path) || !is_writable($path)) {
			throw new DirectoryNotFoundException("Thumbnail directory '{$path}' has not been found");
		}
		$this->thumbDir = $path;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getThumbDir()
	{
		return $this->thumbDir;
	}

	/**
	 * @param string $path
	 * @return $this
	 * @throws DirectoryNotFoundException
	 */
	public function setWwwDir($path)
	{
		if (!is_dir($path) || !is_writable($path)) {
			throw new DirectoryNotFoundException("WWW directory '{$path}' has not been found");
		}
		$this->wwwDir = $path;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getWwwDir()
	{
		return $this->wwwDir;
	}

	/**
	 * @param double $days
	 * @return $this
	 * @throws \Kappa\ThumbnailsHelper\InvalidArgumentException
	 */
	public function setControlFrequency($days)
	{
		if (!is_numeric($days)) {
			throw new InvalidArgumentException("Control frequency must be count of days (int, double)");
		}
		$this->controlFrequency = $days;

		return $this;
	}

	/**
	 * @return double
	 */
	public function getControlFrequency()
	{
		return $this->controlFrequency;
	}
} 