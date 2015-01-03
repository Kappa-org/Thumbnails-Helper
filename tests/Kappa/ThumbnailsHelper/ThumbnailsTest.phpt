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
use Kappa\ThumbnailsHelper\Thumbnails;
use Nette\Utils\Image;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ThumbnailsTest extends TestCase
{
	/** @var \Kappa\ThumbnailsHelper\Configurator */
	private $configurator;

	/** @var string */
	private $wwwDir;

	/** @var string */
	private $thumbDir;

	protected function setUp()
	{
		$this->wwwDir = realpath(__DIR__ . '/../../data/www');
		$this->thumbDir = $this->wwwDir . '/thumb';
		$this->configurator = new Configurator();
		$this->configurator->setWwwDir($this->wwwDir);
		$this->configurator->setThumbDir($this->thumbDir);
		if (!is_dir($this->thumbDir)) {
			mkdir($this->thumbDir);
			$this->thumbDir = realpath($this->thumbDir);
		}
	}

	public function testCreateThumbName()
	{
		$thumbnails = new Thumbnails($this->configurator);
		$thumbnails->setSource($this->wwwDir . '/PHP-logo.png')
			->setSizes('10x10')
			->setFlag('fill');
		$fs = new \FilesystemIterator($this->thumbDir, \FilesystemIterator::SKIP_DOTS);
		Assert::same(0, iterator_count($fs));
		$spl = $thumbnails->getThumb();
		Assert::type('\Kappa\FileSystem\SplFileInfo', $spl);
		$expected = getimagesize($spl->getPathname());
		$image = Image::fromFile($spl->getPathname());
		Assert::true($expected[0] <= $image->getWidth());
		Assert::true($expected[1] <= $image->getHeight());
		Assert::same('/thumb/' . $spl->getBasename(), $spl->getRelativePath($this->wwwDir));
		Assert::true(is_file($spl->getPathname()));
		Assert::same($spl->getMTime(), $thumbnails->getThumb()->getMTime());

		unlink($spl->getPathname());

		$configurator = $this->configurator->setSizeUp(false);
		$thumbnails = new Thumbnails($configurator);
		$thumbnails->setSizes('1000x1000');
		$thumbnails->setSource($this->wwwDir . '/PHP-logo.png');
		$spl = $thumbnails->getThumb();
		$image = Image::fromFile($spl->getPathname());
		$expected = getimagesize($this->wwwDir . '/PHP-logo.png');
		Assert::true($expected[0] <= $image->getWidth());
		Assert::true($expected[1] <= $image->getHeight());
		Assert::same($this->wwwDir . '/PHP-logo.png', $spl->getPathname());
	}

	public function testSameSize()
	{
		$img = $this->wwwDir . '/PHP-logo.png';
		$originalSize = getimagesize($img);
		$thumbnails = new Thumbnails($this->configurator);
		$thumbnails->setSource($this->wwwDir . '/PHP-logo.png')
			->setSizes("{$originalSize[0]}x{$originalSize[1]}")
			->setFlag('fill');
		Assert::same($thumbnails->getThumb()->getRelativePath($this->wwwDir), '/PHP-logo.png');
	}
}

\run(new ThumbnailsTest());
