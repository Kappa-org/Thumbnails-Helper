<?php
/**
 * This file is part of the Kappa\ThumbnailsHelper package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThumbnailsHelper\DI;

use Nette\DI\CompilerExtension;

/**
 * Class ThumbnailsHelperExtension
 * @package Kappa\ThumbnailsHelper\DI
 */
class ThumbnailsHelperExtension extends CompilerExtension
{
	/** @var array */
	private $defaultConfig = array(
		'thumbDir' => '%wwwDir%/thumb',
		'wwwDir' => '%wwwDir%',
		'sizeUp' => false,
		'controlFrequency' => false
	);

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaultConfig);
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('configurator'))
			->setClass('Kappa\ThumbnailsHelper\Configurator')
			->addSetup('setThumbDir', array($config['thumbDir']))
			->addSetup('setWwwDir', array($config['wwwDir']))
			->addSetup('setSizeUp', array($config['sizeUp']))
			->addSetup('setControlFrequency', array($config['controlFrequency']));

		$builder->addDefinition($this->prefix('storage'))
			->setClass('Kappa\ThumbnailsHelper\Storage', array($this->prefix('@configurator')));

		$builder->addDefinition($this->prefix('thumbnailsHelper'))
			->setClass('Kappa\ThumbnailsHelper\ThumbnailsHelper', array(
				$this->prefix('@configurator'),
				$this->prefix('@storage')
			));
	}
}