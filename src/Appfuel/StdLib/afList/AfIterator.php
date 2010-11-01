<?php

class AfIterator implements \Countable, \Iterator
{
    /**
     * Data
     * Holds the elements of the list
     * @var array
     */
    private $data = array();

    /**
     * 
     * @param   array   $data       config data
     * @param   bool    $modify     determines if the config can be modified
     * @return  Config
     */
    public function __construct(array $data = array()) 
    {
        $this->loadData($data); 
    }

    /**
     * Get
     * Acts as the getter method for data items. Also allows the default
     * return value to be set when data item is not found
     *
     * @param   string  $key        data label 
     * @param   mixed   $default    value returned used when data not found
     * @return  mixed
     */
    public function get($key, $default = NULL)
    {
        if (! $this->exists($key)) {
            return $default;
        }

        return $this->data[$key];
    }

    /**
     * Defined by Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Current
     * Iterator implementation that return array item in $_data
     * 
     * @return  mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Key
     * Iterator implementation that returns the index element of the current 
     * position in $_data
     * 
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Next
     * Iterator implementation that advances the internal array pointer of 
     * $_data and increments index
     *
     * @return void
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * Rewind
     * Iterator implementation that moves the internal array pointer of
     * $_data to the beginning
     *
     * @return void
     */
    public function rewind()
    {
        return reset($this->data);
    }

    /**
     * Valid
     * Iterator implementation that checks if index is in range
     *
     * @return  bool
     */
    public function valid()
    {
        return ;
    }

    /**
     * @param   array   $data   config data
     * @param   bool    $modify can this data be changed
     * @return  void
     */
    protected function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->add($key, $value, $readOnly);
        }

    }

    /**
     * Add
     * Introduces a new item into the config data. Add does not
     * update the count of config items
     *
     * @param   string  $key        label for config data item
     * @param   mixed   $value      config item
     * @param   bool    $readOnly   determine if this item can change
     * @return  void
     */
    protected function add($key, $value)
    {
        
        if (is_array($value)) {
            $this->data[$key] = $this->createConfig($value, $readOnly);
        } else {
            $this->data[$key] = $value;
        }
    }

    /**
     * Create 
     * Factory method used to create a config object
     *
     * @param   array   $data
     * @param   bool    $readOnly     determines if this is read-only
     * @return  Config
     */
    protected function create(array $data)
    {
        return new self($data, $readOnly);
    }
}
