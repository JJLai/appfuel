<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Appfuel\Kernel\Mvc;

/**
 * Provides access details for a given route. Details include:
 * 1) is the route public (no acl required)
 * 2) is the route internal (only called by another action)
 * 3) will the route ignore acl (mostly used by internal routes)
 * 4) is acl for each method (get, post, put, delete and cli)
 */
interface RouteAccessInterface
{
	/**
	 * @return RouteAccess
	 */
	public function enablePublicAccess();

	/**
	 * @return RouteAccess
	 */
	public function disablePublicAccess();

	/**
	 * @return	bool
	 */
	public function isPublicAccess();

	/**
	 * @return	RouteAccess
	 */
	public function enableInternalOnlyAccess();

	/**
	 * @return	RouteAccess
	 */
	public function disableInternalOnlyAccess();

	/**
	 * @return	bool
	 */
	public function isInternalOnlyAccess();

	/**
	 * @return	RouteAccess
	 */
	public function ignoreAclAccess();

	/**
	 * @return	RouteAccess
	 */
	public function useAclAccess();

	/**
	 * @return bool
	 */
	public function isAclAccessIgnored();

	/**
	 * @return	RouteAccess
	 */
	public function useAclForAllMethods();

	/**
	 * @return	RouteAccess
	 */
	public function useAclForEachMethod();

	/**
	 * @return	bool
	 */
	public function isAclForEachMethod();

	/**
	 * @param	string	$code
	 * @return	bool
	 */
	public function isAccessAllowed($codes, $method = null);

	/**
	 * @param	mixed string | array
	 * @return	RouteAccess
	 */
	public function setAclMap(array $map);

	/**
	 * @return	RouteAccess
	 */
	public function getAclMap();
}
