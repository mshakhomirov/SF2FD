<?php
/**
 * Replacement of StdClass with overrided getters/setters.
 */
class SimpleObject {
    /**
     * Returns object property
     * @param string $key Property name
     * @return mixed Value of object property
     */
    function __get($key)
    {
        return (isset($this->{$key})) ? $this->{$key} : NULL;
    }
    
    /**
     * Returns object property
     * @param string $key Property name
     * @return mixed Value of object property
     */
    public function get($key)
    {
        return (isset($this->{$key})) ? $this->{$key} : NULL;
    }
    
    /**
     * Set property value
     * @param string $key Property name
     * @param mixed $value Value
     */
    public function set($key, $value)
    {
        $this->{$key} = $value;
    }
}