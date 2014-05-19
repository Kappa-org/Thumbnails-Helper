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
use Nette\DI\Container;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class ThumbnailsHelperExtensionTest
 * @package Kappa\ThumbnailsHelper\Tests
 */
class ThumbnailsHelperExtensionTest extends TestCase
{
	private $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	public function testHelper()
	{
		$service = $this->container->getService('thumb.thumbnailsHelper');
		Assert::type('Kappa\ThumbnailsHelper\ThumbnailsHelper', $service);
	}

	public function testConfigurator()
	{
		$service = $this->container->getService('thumb.configurator');
		Assert::type('Kappa\ThumbnailsHelper\Configurator', $service);
	}

	public function testStorage()
	{
		$service = $this->container->getService('thumb.storage');
		Assert::type('Kappa\ThumbnailsHelper\Storage', $service);
	}
}

\run(new ThumbnailsHelperExtensionTest(getContainer()));