<?php
/**
 * This file is part of the Kappa\ThumbnailsHelper package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 * 
 * @testCase
 */

namespace Kappa\ThumbnailsHelper\Tests;

use Kappa\FileSystem\Directory;
use Kappa\Tester\TestCase;
use Kappa\ThumbnailsHelper\Configurator;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ConfiguratorTest
 * @package Kappa\ThumbnailsHelper\Tests
 */
class ConfiguratorTest extends TestCase
{
	public function testConfigurator()
	{
		$configurator = new Configurator();
		Assert::type('Kappa\ThumbnailsHelper\Configurator', $configurator->setThumbDir(__DIR__));
		Assert::type('Kappa\ThumbnailsHelper\Configurator', $configurator->setWwwDir(__DIR__ . '/../'));
		Assert::type('Kappa\ThumbnailsHelper\Configurator', $configurator->setControlFrequency(0.5));
		Assert::type('Kappa\ThumbnailsHelper\Configurator', $configurator->setSizeUp(true));
		Assert::type('Kappa\FileSystem\Directory', $configurator->getThumbDir());
		Assert::same(__DIR__, $configurator->getThumbDir()->getInfo()->getPathname());
		Assert::type('Kappa\FileSystem\Directory', $configurator->getWwwDir());
		Assert::same(realpath(__DIR__ . '/../'), $configurator->getWwwDir()->getInfo()->getPathname());
		Assert::same(0.5, $configurator->getControlFrequency());
		Assert::true($configurator->getSizeUp());

		Assert::throws(function() use($configurator) {
			$configurator->setThumbDir('thumbDir');
		}, 'Kappa\ThumbnailsHelper\DirectoryNotFoundException');
		Assert::throws(function() use($configurator) {
			$configurator->setWwwDir('wwwDir');
		}, 'Kappa\ThumbnailsHelper\DirectoryNotFoundException');
		Assert::throws(function() use($configurator) {
			$configurator->setControlFrequency('Hello');
		}, 'Kappa\ThumbnailsHelper\InvalidArgumentException');
	}
}

\run(new ConfiguratorTest());