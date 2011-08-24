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
namespace Appfuel\Framework\Action;


/**
 */
interface ActionControllerDetailInterface
{
    /**
     * @return string
     */
    public function getActionNamespace();

    /**
     * @return string
     */
    public function getSubModuleNamespace();

    /**
     * @return string
     */
    public function getModuleNamespace();

    /**
     * @return string
     */
    public function getRootNamespace();

}
