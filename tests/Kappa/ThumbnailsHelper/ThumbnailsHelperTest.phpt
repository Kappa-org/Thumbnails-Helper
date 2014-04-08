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

use Kappa\Tester\TestCase;
use Kappa\ThumbnailsHelper\Configurator;
use Kappa\ThumbnailsHelper\ThumbnailsHelper;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThumbnailsHelperTest
 * @package Kappa\ThumbnailsHelper\Tests
 */
class ThumbnailsHelperTest extends TestCase
{
	/** @var \Kappa\ThumbnailsHelper\ThumbnailsHelper */
	private $thumbnailHelper;

	protected function setup()
	{
		$configurator = new Configurator();
		$configurator->setWwwDir(__DIR__ . '/../../data/www')
			->setThumbDir(__DIR__ . '/../../data/www/thumb');
		$this->thumbnailHelper = new ThumbnailsHelper($configurator);
	}

	public function testProcess()
	{
		$path = $this->thumbnailHelper->process('/PHP-logo.png', '10x10');
		Assert::match('~^/thumb/[a-z0-9]*.png$~i', $path);

		unlink(__DIR__ . '/../../data/www' . $path);
	}
}

\run(new ThumbnailsHelperTest());