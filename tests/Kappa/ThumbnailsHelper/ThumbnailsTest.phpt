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

use Kappa\Tester\TestCase;
use Kappa\ThumbnailsHelper\DataProvider;
use Kappa\ThumbnailsHelper\Manager;
use Kappa\ThumbnailsHelper\Thumbnails;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

/**
 * Class ThumbnailsTest
 * @package Kappa\Tests\ThumbnailsHelper
 */
class ThumbnailsTest extends TestCase
{
	/** @var \Kappa\ThumbnailsHelper\Thumbnails */
	private $thumbnails;

	/** @var string */
	private $wwwDir;

	/** @var string */
	private $thumbDir;

	protected function setUp()
	{
		$dataProvider = new DataProvider();
		$this->wwwDir = __DIR__ . '/../../data/www';
		$this->thumbDir = __DIR__ . '/../../data/www/thumb';
		$dataProvider->setWwwDir($this->wwwDir);
		$dataProvider->setThumbDir($this->thumbDir);
		$dataProvider->setFrequency(0.000001);
		$manager = new Manager($dataProvider);
		$this->thumbnails = new Thumbnails($dataProvider, $manager);
	}

	public function testProcess()
	{
		Assert::same($this->generateThumbName(), $this->thumbnails->process('/PHP-logo.png', array(10, 10)));
	}

	/**
	 * @return string
	 */
	private function generateThumbName()
	{
		return DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . 'PHP-logo_thumb10x10_' . md5(realpath($this->wwwDir . DIRECTORY_SEPARATOR . 'PHP-logo.png')) . '_' . filemtime(realpath($this->wwwDir . '/PHP-logo.png')) . '.png';
	}

	protected function tearDown()
	{
		\Tester\Helpers::purge($this->thumbDir);
	}
}

\run(new ThumbnailsTest());