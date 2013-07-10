<?php
/**
 * This file is part of the Kappa package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\ThumbnailsHelper;

/**
 * Class DirectoryNotFoundException
 * @package Kappa\ThumbnailsHelper
 */
class DirectoryNotFoundException extends IOException
{

}

/**
 * Class InvalidArgumentException
 * @package Kappa\ThumbnailsHelper
 */
class InvalidArgumentException extends \LogicException
{

}

/**
 * Class IOException
 * @package Kappa\ThumbnailsHelper
 */
class IOException extends \LogicException
{

}