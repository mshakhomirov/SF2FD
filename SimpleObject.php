<?php

class SimpleObject {

<?php

class SimpleObject {



    function __get($key)

    {

        return (isset($this->{$key})) ? $this->{$key} : NULL;

    }

    


    public function get($key)

    {

        return (isset($this->{$key})) ? $this->{$key} : NULL;

    }





    public function set($key, $value)

    {

        $this->{$key} = $value;

    }

}

