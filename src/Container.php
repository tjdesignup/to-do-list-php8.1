<?php

namespace App;

class Container{
    private array $services =[];

    public function set(string $key, callable $factory)
    {
        $this->services[$key] = $factory;
    }

    public function get(string $key)
    {
        if(isset($this->services[$key])){
            return $this->services[$key]($this);
        }

        throw new \Exception("Service '$key' not found.");
    }
}