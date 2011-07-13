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
namespace Appfuel\Framework\Db\Adapter;

use Appfuel\Framework\Db\Request\RequestInterface;

/**
 * Adapter interface that governs functionality for database queries
 */
interface QueryAdapterInterface extends AdapterInterface
{
	public function execute(RequestInterface $request);
}