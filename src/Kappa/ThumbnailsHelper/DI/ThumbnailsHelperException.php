<?php
/**
 * This file is part of the Kappa/ThumbnailsHelper package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThumbnailsHelper\DI;

use Nette\DI\CompilerExtension;

/**
 * Class ThumbnailsHelperException
 * @package Kappa\ThumbnailsHelper\DI
 */
class ThumbnailsHelperException extends CompilerExtension
{
	/** @var array */
	private $defaultConfig = array(
		'wwwDir' => '%wwwDir%',
		'thumbDir' => '%wwwDir%/thumb',
		'frequency' => null,
	);

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig($this->defaultConfig);
		$builder->addDefinition('thumbnails.manager')
			->setClass('\Kappa\ThumbnailsHelper\Manager')
			->addSetup('setThumbDir', array($config['thumbDir']))
			->addSetup('setFrequency', array($config['frequency']));
		$builder->addDefinition($this->prefix("thumbnails.helper"))
			->setClass('Kappa\ThumbnailsHelper\Thumbnails', array('@thumbnails.manager'))
			->addSetup('setWwwDir', array($config['wwwDir']));
	}
}