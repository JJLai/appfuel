<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.code@gmail.com.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Appfuel\App\View;

use Appfuel\Framework\Exception,
	Appfuel\Framework\FileInterface,
	Appfuel\Framework\App\View\ViewManagerInterface;

/**
 * Handles assignments to the view 
 */
class ViewManager implements ViewManagerInterface
{
	/**
	 * Type of view currently being used. Html, Cli, Json, Csv, Null etc..
	 * @var string
	 */
	protected $viewType = null;
}
