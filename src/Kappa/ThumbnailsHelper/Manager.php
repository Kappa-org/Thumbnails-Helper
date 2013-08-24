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

/**
 * Class Manager
 * @package Kappa\ThumbnailsHelper
 */
class Manager implements IManager
{
	/** @var DataProvider */
	private $dataProvider;

	const CONTROL_FILE = '_kappa-thumbnails-helper-control-file.txt';

	/**
	 * @param DataProvider $dataProvider
	 */
	public function __construct(DataProvider $dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}

	/**
	 * Check all thumbnails and delete older
	 */
	public function check()
	{
		if ($this->dataProvider->getFrequency() !== null) {
			$controlFile = new File(self::CONTROL_FILE);
			if ($controlFile->isUsable()) {
				$lastControl = $controlFile->read();
				$time = strtotime("now") - (60 * 60 * 24 * $this->dataProvider->getFrequency());
				if ($lastControl <= $time) {
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
		$files = $this->dataProvider->getThumbDir()->getFiles();
		/** @var $file \Kappa\FileSystem\File */
		foreach ($files as $path => $file) {
			if ($file->getInfo()->getMTime() <= $time) {
				if (!$file->remove()) {
					throw new IOException("File {$file->getInfo->getBasename()} has not been removed");
				}
			}
		}
	}
}