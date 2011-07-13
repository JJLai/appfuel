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
namespace Appfuel\Db\Handler;

use Appfuel\Framework\Exception,
	Appfuel\Framework\Db\PoolInterface;

/**
 * The database handler is responsible for handling database requests without
 * knowledge of the database vendor or what adapter is used with that vendor.
 * Its only job is to grab the correct connection, use it to create an 
 * adaoter  and decide which adapter method to use based on the request, 
 * finishing by returning a DbResponse.
 */
class DbHandler
{
	/**
	 * The pool hold one or more connections for the database
	 * @var	ConnectionInterface
	 */
	static protected $pool = null;

	/**
	 * @return	PoolInterface
	 */
	static public function getPool()
	{
		return self::$pool;
	}

	/**
	 * @param	PoolInterface $pool
	 * @return	null
	 */
	static public function setPool(PoolInterface $pool)
	{
		self::$pool = $pool;
	}

	/**
	 * @return bool
	 */
	static public function isPool()
	{
		return self::$pool instanceof PoolInterface;
	}

	/**
	 * close all the connections in the pool and unset it
	 *
	 * @return	true
	 */
	static public function clearPool()
	{
		if (! self::isPool()) {
			return true;
		}

		self::$pool->shutdown();
		self::$pool = null;
		return true;
	}

	public function execute(DbRequestInterface $request)
	{
	
	}

	/**
	 * returns a connection from the pool base on the type
	 *
	 * @return	ConnectionInterface | null
	 */
	public function getConnection($type)
	{

	}
}