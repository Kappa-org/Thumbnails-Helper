<?php
/**
 * ThumbnailsHelperTest.phpt
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 13.5.13
 *
 * @package Kappa
 */
 
namespace Kappa\Tests\ThumbnailsHelper\ThumbnailsHelper;

use Kappa\Tester\TestCase;
use Kappa\ThumbnailsHelper\ThumbnailsHelper;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';

class ThumbnailsHelperTest extends TestCase
{
	/** @var string */
	private $thumbDir;

	/** @var string */
	private $wwwDir;

	public function __construct()
	{
		$this->thumbDir = realpath(__DIR__ . '/../../data/www/thumb');
		$this->wwwDir = realpath(__DIR__ . '/../../data/www/');
	}

	/**
	 * @param array $expected
	 * @dataProvider provideConstruct
	 */
	public function testConstruct(array $expected)
	{
		\Tester\Helpers::purge($this->thumbDir);
		$thumb = new ThumbnailsHelper($this->wwwDir, $this->thumbDir);
		Assert::same($expected, $this->getReflection()->invokeProperty($thumb, 'params'));
		Assert::false(file_exists($this->thumbDir . '/lastControl.txt'));
		Assert::true(new ThumbnailsHelper($this->wwwDir, $this->thumbDir, 1) instanceof ThumbnailsHelper);
		Assert::true(file_exists($this->thumbDir . '/lastControl.txt'));
	}

	/**
	 * @param string $expected
	 * @dataProvider provideProcess
	 */
	public function testProcess($expected)
	{
		\Tester\Helpers::purge($this->thumbDir);
		$thumb = new ThumbnailsHelper($this->wwwDir, $this->thumbDir, 0.0000001);
		Assert::same($expected, $thumb->process('/PHP-logo.png', array(10,10)));
	}

	/** Providers */

	/**
	 * @return array
	 */
	public function provideConstruct()
	{
		return array(
			array(array('wwwDir' => $this->wwwDir, 'thumbDir' => $this->thumbDir)),
		);
	}

	/**
	 * @return array
	 */
	public function provideProcess()
	{
		return array(
			array(DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR . 'PHP-logo_thumb10x10_b028f6dacc9bf55567b1506c28828189_1368390315.png'),
		);
	}

	protected function tearDown()
	{
		\Tester\Helpers::purge($this->thumbDir);
	}
}

\run(new ThumbnailsHelperTest());