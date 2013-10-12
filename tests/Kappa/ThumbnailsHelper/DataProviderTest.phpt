<?php
/**
 * This file is part of the Kappa/ThumbnailsHelper package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 * 
 * @testCase
 */

namespace Kappa\Tests\ThumbnailsHelper;

use Kappa\FileSystem\Directory;
use Kappa\Tester\TestCase;
use Kappa\ThumbnailsHelper\DataProvider;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class DataProviderTest extends TestCase
{
	/** @var \Kappa\ThumbnailsHelper\DataProvider */
	private $dataProvider;

	protected function setUp()
	{
		$this->dataProvider = new DataProvider();
	}

	public function testThumbDir()
	{
		Assert::null($this->dataProvider->getThumbDir());
		$this->dataProvider->setThumbDir(__DIR__);
		Assert::equal(new Directory(__DIR__, Directory::LOAD), $this->dataProvider->getThumbDir());
		Assert::throws(function () {
			$this->dataProvider->setThumbDir('/non-exist');
		}, '\Kappa\FileSystem\DirectoryNotFoundException');
	}

	public function testWwwDir()
	{
		Assert::null($this->dataProvider->getWwwDir());
		$this->dataProvider->setWwwDir(__DIR__);
		Assert::equal(new Directory(__DIR__, Directory::LOAD), $this->dataProvider->getWwwDir());
		Assert::throws(function () {
			$this->dataProvider->setWwwDir('/non-exist');
		}, '\Kappa\FileSystem\DirectoryNotFoundException');
	}

	public function testFrequency()
	{
		Assert::null($this->dataProvider->getFrequency());
		$this->dataProvider->setFrequency(5);
		Assert::equal(5, $this->dataProvider->getFrequency());
		Assert::throws(function () {
			$this->dataProvider->setFrequency(array());
		}, '\Kappa\ThumbnailsHelper\InvalidArgumentException');
	}
}

\run(new DataProviderTest());