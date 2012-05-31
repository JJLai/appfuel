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

/**
 * Coordinator handles the movement of raw, clean, and error data so that
 * the other subsystems don't have to deal with it
 */
interface CoordinatorInterface
{
	/**
	 * Value used to indicate a raw field was not found. This allows you to
	 * use getRaw and trust that a null or false  is the legitimate return 
	 * value and not a failure case.
	 */
	const FIELD_NOT_FOUND = '___AF_FIELD_NOT_FOUND___';

    /**
     * @return array
     */
    public function getAllClean();

    /**
     * @param   string  $label
     * @param   mixed   $value
     * @return  CoordinatorInterface
     */
    public function addClean($field, $value);

    /**
     * @param   string  $field
     * @param   mixed   $default
     * @return  mixed
     */
    public function getClean($field, $default = null);

	/**
	 * @return	CoordinatorInterface
	 */
	public function clearClean();

    /**
     * @return array
     */
    public function getSource();

    /**
     * @param   array	$source
     * @return  CoordinatorInterface
     */
    public function setSource(array $source);

    /**
     * @param   string  $field
     * @return  mixed
     */
    public function getRaw($field);

	/**
	 * @return	CoordinatorInterface
	 */
	public function clearSource();

	/**
	 * Returns a special token string to indicate that the raw key was
	 * not located
	 *
	 * @return	string
	 */
	public function getFieldNotFoundToken();
    
	/**
	 * @param	string	$field
     * @param   string  $msg
     * @return  FilterValidator
     */
    public function addError($field, $msg);
    
	/**
     * @return bool
     */
    public function isError();

    /**
     * @return string
     */
    public function getErrorStack();
}
