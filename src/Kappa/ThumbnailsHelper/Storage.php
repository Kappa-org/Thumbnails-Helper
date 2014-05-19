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
use Kappa\FileSystem\FileSystem;

/**
 * Class ThumbStorage
 * @package Kappa\ThumbnailsHelper
 */
class Storage
{
	const CONTROL_FILE = '.controlFile';

	/** @var \Kappa\ThumbnailsHelper\Configurator */
	private $configurator;

	/**
	 * @param Configurator $configurator
	 */
	public function __construct(Configurator $configurator)
	{
		$this->configurator = $configurator;
	}

	public function invalidateStorage()
	{
		if ($this->configurator->getControlFrequency() !== false) {
			if ($this->needInvalidate()) {
				/** @var \Kappa\FileSystem\File $file */
				foreach ($this->configurator->getThumbDir()->getFiles() as $file) {
					if ($file->getInfo()->getBasename() != self::CONTROL_FILE) {
						FileSystem::remove($file);
					} else {
						$file->overwrite(time());
					}
				}
			}
		}
	}

	/**
	 * @return bool
	 */
	public function needInvalidate()
	{
		$storage = $this->configurator->getThumbDir();
		$controlFilePath = $storage->getInfo()->getPathname() . DIRECTORY_SEPARATOR . self::CONTROL_FILE;
		if (is_file($controlFilePath)) {
			$controlFile = File::open($controlFilePath);
		} else {
			$controlFile = File::create($controlFilePath, time());
		}
		$lastInvalidate = $controlFile->read();
		if ($lastInvalidate <= time() - ($this->configurator->getControlFrequency() * 24 * 60 * 60)) {
			return true;
		} else {
			return false;
		}
	}
}