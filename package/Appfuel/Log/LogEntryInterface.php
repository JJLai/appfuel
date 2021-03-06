<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.code@gmail.com.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Appfuel\Log;

/**
 * A single entry in the log
 */
interface LogEntryInterface
{
	/**
	 * @return	string
	 */
	public function getTimestamp();

	/**
	 * @return	string
	 */
	public function getText();

	/**
	 * @return	LogPriorityInterface
	 */
	public function getPriority();

	/**
	 * @return	mixed
	 */
	public function getPriorityLevel();

	/**
	 * @return	string
	 */
	public function getEntry();

	/**
	 * @return	string
	 */
	public function __toString();

}
