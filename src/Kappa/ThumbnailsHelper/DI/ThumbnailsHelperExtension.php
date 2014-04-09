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

use Flame\Modules\Providers\ITemplateHelpersProvider;
use Nette\DI\CompilerExtension;

/**
 * Class ThumbnailsHelperExtension
 * @package Kappa\ThumbnailsHelper\DI
 */
class ThumbnailsHelperExtension extends CompilerExtension implements ITemplateHelpersProvider
{
	public function loadConfiguration()
	{
		$config = $this->getConfig(array(
			'thumbDir' => '%wwwDir%/thumb',
			'wwwDir' => '%wwwDir%',
			'sizeUp' => false,
			'controlFrequency' => false
		));
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('configurator'))
			->setClass('Kappa\ThumbnailsHelper\Configurator')
			->addSetup('setThumbDir', array($config['thumbDir']))
			->addSetup('setWwwDir', array($config['wwwDir']))
			->addSetup('setSizeUp', array($config['sizeUp']))
			->addSetup('setControlFrequency', array($config['controlFrequency']));

		$builder->addDefinition($this->prefix('thumbStorage'))
			->setClass('Kappa\ThumbnailsHelper\ThumbStorage', array($this->prefix('@configurator')));

		$builder->addDefinition($this->prefix('thumbnailsHelper'))
			->setClass('Kappa\ThumbnailsHelper\ThumbnailsHelper', array(
				$this->prefix('@configurator'),
				$this->prefix('@thumbStorage')
			));
	}

	/**
	 * Return list of helpers definitions or providers
	 *
	 * @return array
	 */
	public function getHelpersConfiguration()
	{
		$config = $this->getConfig(array('name' => 'thumb'));
		return array(
			$config['name'] => array($this->prefix('@thumbnailsHelper'), 'process')
		);
	}
}