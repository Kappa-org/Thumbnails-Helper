<?php
/**
 * ThumbnailsHelperTest.phpt
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 6.2.13
 *
 * @package Kappa
 */

namespace Kappa\Tests\Templating\Helpers;

use Kappa\Tests;
use Kappa\Templating\Helpers\ThumbnailsHelper;
use Kappa\Utils\FileSystem\Files;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

class ThumbnailsHelperTest extends Tests\TestCase
{
	/** @var \Kappa\Templating\Helpers\ThumbnailsHelper */
	private $thumbnailsHelper;

	/** @var \Kappa\Templating\Helpers\ThumbnailsHelper */
	private $thumbnailsHelperFail;



	protected function setUp()
	{
		$this->thumbnailsHelper = new ThumbnailsHelper(__DIR__ . '/../../../data/www', '/thumb', 1);
		Assert::throws(function () {
			$this->thumbnailsHelper = new ThumbnailsHelper(__DIR__ . '/../../../data/fails', '/thumb', 1);
		}, '\Kappa\DirectoryNotFoundException');
		Assert::throws(function () {
			$this->thumbnailsHelper = new ThumbnailsHelper(__DIR__ . '/../../../data/www', '/thumb', array());
		}, '\Kappa\InvalidArgumentException');
	}



	/**
	 * @param array $params
	 * @dataProvider providerConstruct
	 */
	public function testConstruct(array $params = array())
	{
		Assert::true($this->thumbnailsHelper instanceof ThumbnailsHelper);
		Assert::equal($params, $this->invokeProperty($this->thumbnailsHelper, 'params'));
	}



	public function testCheckThumbDir()
	{
		Assert::same(realpath(__DIR__ . '/../../../data/www/thumb'), $this->invokeMethod($this->thumbnailsHelper, 'checkThumbDir', array('/thumb')));
		Assert::true(file_exists(realpath(__DIR__ . '/../../../data/www/thumb')));
		$this->invokeMethod($this->thumbnailsHelper, 'checkThumbDir', array('/thumb2'));
		Assert::true(file_exists(realpath(__DIR__ . '/../../../data/www/thumb2')));
		\Kappa\Utils\FileSystem\Directories::recursiveDelete(realpath(__DIR__ . '/../../../data/www/thumb2'));
	}



	public function testThumb()
	{
		$md5 = Files::md5sum(realpath(__DIR__ . '/../../../data/www/PHP-logo.png'));
		$time = fileatime(realpath(__DIR__ . '/../../../data/www/PHP-logo.png'));
		$file = DIRECTORY_SEPARATOR  . 'thumb' . DIRECTORY_SEPARATOR;
		$file .= 'PHP-logo_thumb300x200_' . $md5 . '_' . $time . '.png';
		Assert::same($file, $this->thumbnailsHelper->thumb('/PHP-logo.png', array(300,200), "FIT"));
		Files::deleteFiles(realpath(__DIR__ . '/../../../data/www'.$file));
		Assert::throws(function () {
			$this->thumbnailsHelper->thumb("un-exist-file");
		}, '\Kappa\FileNotFoundException');
		Assert::throws(function () {
			$this->thumbnailsHelper->thumb("/PHP-logo.png", array(300,200), "LOL");
		}, '\Kappa\InvalidArgumentException');
	}



	/**
	 * @return array
	 */
	public function providerConstruct()
	{
		return array(
			array(
				array(
					'wwwDir' => realpath(__DIR__ . '/../../../data/www'),
					'thumbDir' => realpath(__DIR__ . '/../../../data/www/thumb'),
					'frequencyControl' => 1
				)
			)
		);
	}
	
}

\run(new ThumbnailsHelperTest());