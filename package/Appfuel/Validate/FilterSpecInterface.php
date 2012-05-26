<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.appfuel@gmail.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.appfuel@gmail.com>
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Appfuel\Validate;

use DomainException,
	InvalidArgumentException,
	Appfuel\DataStructure\Dictionary,
	Appfuel\DataStructure\DictionaryInterface;

/**
 * Value object used to hold information about a filter
 */
interface FilterSpecInterface
{
	/**
	 * @return	string
	 */
	public function getName();

	/**
	 * @return	array
	 */
	public function getParams();

	/**
	 * @return	bool
	 */
	public function isParams();

	/**
	 * @return	string
	 */
	public function getError();
}