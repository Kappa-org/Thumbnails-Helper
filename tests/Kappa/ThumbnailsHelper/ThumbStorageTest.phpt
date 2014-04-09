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
use Kappa\ThumbnailsHelper\ThumbStorage;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThumbStorageTest
 * @package Kappa\ThumbnailsHelper\Tests
 */
class ThumbStorageTest extends TestCase
{
	private $thumbDir;

	protected function setUp()
	{
		$this->thumbDir = __DIR__ . '/../../data/www/thumb';
		if (!is_dir($this->thumbDir)) {
			mkdir($this->thumbDir);
		}
	}

	public function testNeedInvalidate()
	{
		$configurator = new Configurator();
		$configurator->setThumbDir($this->thumbDir)
			->setControlFrequency(10);
		$configurator2 = new Configurator();
		$configurator2->setThumbDir($this->thumbDir)
			->setControlFrequency(0);
		$thumbStorage = new ThumbStorage($configurator);
		$thumbStorage2 = new ThumbStorage($configurator2);
		Assert::false($thumbStorage->needInvalidate());
		Assert::true($thumbStorage2->needInvalidate());

		unlink($this->thumbDir . '/.controlFile');
	}

	public function testInvalidateStorage()
	{
		$configurator = new Configurator();
		$configurator->setThumbDir($this->thumbDir)
			->setControlFrequency(0);
		$thumbStorage = new ThumbStorage($configurator);
		$thumbStorage->invalidateStorage();
		$expected = file_get_contents($this->thumbDir . '/.controlFile');
		file_put_contents($this->thumbDir . '/some', '');
		Assert::true(is_file($this->thumbDir . '/some'));
		sleep(2);
		$thumbStorage->invalidateStorage();
		Assert::false(is_file($this->thumbDir . '/some'));
		$actual = file_get_contents($this->thumbDir . '/.controlFile');
		Assert::notSame($expected, $actual);

		unlink($this->thumbDir . '/.controlFile');
	}
}

\run(new ThumbStorageTest());