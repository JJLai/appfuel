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
namespace Appfuel;

use Appfuel\Stdlib\Data\BagInterface,
	Appfuel\Stdlib\Data\Bag;

/**
 * Global Registry used by the framework to hold config data and handle 
 * startup and intialization
 */
class Registry
{
	/**
	 * List of the necessary files before autoloading
	 * @var array
	 */
	static protected $bag = null;

	/**
	 * Reset the registry with a new dataset and if one is not given
	 * then create an empty one
	 *
	 * @param	BagInterface	$bag
	 * @return	null
	 */
	static public function init($data = null)
	{
		/*
		 * check is there is an array data with the initialization or if
		 * a Bag structure is manually being passed in otherwise create an 
		 * empty bag
		 */
		if (is_array($data)) {
			$bag = new Bag($data);
		} else if ($data instanceof BagInterface) {
			$bag = $data;
		} else {
			$bag = new Bag();
		}

		self::$bag = $bag;
	}

	/**
	 * Alias to initialize to help readability
	 *
	 * @return null
	 */
	static public function clear()
	{
		self::$bag = null;
	}

	/**
	 * @return int
	 */
	static public function count()
	{
		if (! self::isInit()) {
			return 0;
		}

		return self::getBag()->count();
	}

	/**
	 * @param	string	$key
	 * @param	mixed	$default	returned when key is not found
	 * @return	mixed
	 */
	static public function get($key, $default = NULL)
	{
		if (! self::isInit() || ! is_scalar($key)) {
			return $default;
		}

		return self::getBag()->get($key, $default);
	}

	/**
	 *	@param	string	$key
	 *	@param	mixed	$value
	 *  @return	null
	 */
	static public function add($key, $value)
	{
		if (! self::isInit() || ! is_scalar($key)) {
			return false;
		}

		self::getBag()->add($key, $value);
		return true;
	}

	/**
	 * Get all data
	 *
	 * @return array
	 */
	static public function getAll()
	{
		if (! self::isInit()) {
			return array();
		}

		return self::getBag()->getAll();
	}

	/**
	 * Load a dataset into the bag
	 * @param	array	$data	
	 * @return	null
	 */
	static public function load(array $data)
	{
		if (! self::isInit()) {
			return false;
		}
		
		self::getBag()->load($data);
		return true;
	}

	/**
	 * @param	string	$key
	 * @return	bool
	 */
	static public function exists($key)
	{
		if (! self::isInit()) {
			return false;
		}

		return self::getBag()->exists($key);
	}

	/**
	 * Collect a series of name value pairs 
	 * 
	 * @param	array	$list		of keys to collect
	 * @return	array
	 */
	static public function collect(array $list, $array = false)
	{
		if (! self::isInit()) {
			return false;
		}

		$result = array();
		foreach ($list as $index => $key) {
			if (! self::exists($key)) {
				continue;
			}
			$result[$key] = self::get($key);
		}

		if (true === $array) {
			return $result;
		}

		return new Bag($result);
	}

	/**
	 * Has the registry been initialized, so a data bag is ready to use
	 * 
	 * @return bool
	 */
	static public function isInit()
	{
		return self::$bag instanceof BagInterface;
	}

	/**
	 * @return BagInterface
	 */
	static protected function getBag()
	{
		return self::$bag;
	}
}