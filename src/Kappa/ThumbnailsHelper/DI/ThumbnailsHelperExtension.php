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
	public function loadConfiguration()
	{
		$config = $this->getConfig(array(
			'thumbDir' => '%wwwDir%/thumb',
			'sizeUp' => false,
		));
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('@configurator'))
			->setClass('Kappa\ThumbnailsHelper\Configurator')
			->addSetup('setThumbDir', array($config['thumbDir']))
			->addSetup('setWwwDir', array('%wwwDir%'))
			->addSetup('setSizeUp', array($config['sizeUp']));

		$builder->addDefinition($this->prefix('@thumbnailsHelper'))
			->setClass('Kappa\ThumbnailsHelper\ThumbnailsHelper', array($this->prefix('@configurator')));
	}
} 