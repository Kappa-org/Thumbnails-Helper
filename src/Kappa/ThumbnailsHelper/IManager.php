<?php
/**
 * This file is part of the Kappa/ThumbnailsHelper package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThumbnailsHelper;

/**
 * Class IManager
 * @package Kappa\ThumbnailsHelper
 */
interface IManager
{
	/**
	 * Check all thumbnails and delete older
	 *
	 * @return void
	 */
	public function check();
}