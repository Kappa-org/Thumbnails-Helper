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
use Kappa\FileSystem\File;

/**
 * Class Manager
 * @package Kappa\ThumbnailsHelper
 */
class Manager implements IManager
{
	/** @var \Kappa\FileSystem\Directory */
	private $thumbDir;

	/** @var int|string */
	private $frequency = null;

	const CONTROL_FILE = '_kappa-thumbnails-helper-control-file.txt';

	/**
	 * Set path to thumb directory
	 *
	 * @param string $thumbDir
	 * @throws DirectoryNotFoundException
	 */
	public function setThumbDir($thumbDir)
	{
		$this->thumbDir = new Directory($thumbDir);
		if(!$this->thumbDir->isUsable()) {
			throw new DirectoryNotFoundException("Directory {$thumbDir} has not been found");
		}
	}

	/**
	 * Set control frequency
	 *
	 * @param int|string $frequency
	 * @throws InvalidArgumentException
	 */
	public function setFrequency($frequency)
	{
		if(!is_numeric($frequency) && !is_string($frequency)) {
			throw new InvalidArgumentException("Control frequency must be integer or string, " . gettype($frequency) . " given");
		}
		$this->frequency = $frequency;
	}

	/**
	 * Check all thumbnails and delete older
	 */
	public function check()
	{
		if($this->frequency !== null) {
			$controlFile = new File(self::CONTROL_FILE);
			if($controlFile->isUsable()) {
				$lastControl = $controlFile->read();
				$time = strtotime("now") - (60 * 60 * 24 * $this->frequency);
				if($lastControl <= $time) {
					$this->deleteFiles($time);
				}
			} else {
				$controlFile->create();
				$controlFile->overwrite(strtotime('now'));
			}
		}
	}

	/**
	 * Delete older files
	 *
	 * @param int|string $time
	 * @throws IOException
	 */
	private function deleteFiles($time)
	{
		$files = $this->thumbDir->getFiles();
		/** @var $file \Kappa\FileSystem\File */
		foreach($files as $path => $file) {
			if ($file->getInfo()->getMTime() <= $time) {
				if (!$file->remove()) {
					throw new IOException("File {$file->getInfo->getBasename()} has not been removed");
				}
			}
		}
	}
}