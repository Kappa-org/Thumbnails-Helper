<?php
/**
 * exceptions.php
 *
 * @author Ondřej Záruba <zarubaondra@gmail.com>
 * @date 13.5.13
 *
 * @package Kappa\ThumbnailsHelper
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