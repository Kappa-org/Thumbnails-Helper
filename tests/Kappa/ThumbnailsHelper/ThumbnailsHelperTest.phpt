<?php
/**
 * This file is part of the Thumbnails-Helper package.
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
use Kappa\ThumbnailsHelper\ThumbnailsHelper;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ThumbnailsHelperTest extends TestCase
{
	/** @var string */
	private $wwwDir;

	/** @var string */
	private $thumbDir;

	public function setUp()
	{
		$this->wwwDir = __DIR__ . '/../../data/www';
		$this->thumbDir = __DIR__ . '/../../data/www/thumb';
		if(!file_exists($this->thumbDir))
			@mkdir($this->thumbDir);
	}

	public function testConstruct()
	{
		$thumb = new ThumbnailsHelper($this->wwwDir,$this->thumbDir);
		Assert::same(array('wwwDir' => realpath($this->wwwDir), 'thumbDir' => realpath($this->thumbDir)), $this->getReflection()->invokeProperty($thumb, 'params'));
		Assert::false(file_exists($this->thumbDir . '/lastControl.txt'));
		Assert::true(new ThumbnailsHelper($this->wwwDir, $this->thumbDir, 1) instanceof ThumbnailsHelper);
		Assert::true(file_exists($this->thumbDir . '/lastControl.txt'));
	}

	public function testProcess()
	{
		$thumb = new ThumbnailsHelper($this->wwwDir, $this->thumbDir);
		Assert::same($this->generateThumbName(), $thumb->process('/PHP-logo.png', array(10,10)));
	}

	private function generateThumbName()
	{
		return DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . 'PHP-logo_thumb10x10_' . md5_file($this->wwwDir . '/PHP-logo.png') . '_' . filemtime(realpath($this->wwwDir . '/PHP-logo.png')) . '.png';
	}

	protected function tearDown()
	{
		\Tester\Helpers::purge($this->thumbDir);
	}
}

\run(new ThumbnailsHelperTest());