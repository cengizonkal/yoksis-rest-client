<?php


namespace Conkal\YOKSIS\REST\Entities;


abstract class Entity
{
    public function __construct($filaments = null)
    {
        if ($filaments) {
            $this->fill($filaments);
        }
    }

    public function fill($filaments)
    {
        if (is_object($filaments)) {
            $this->fillFromArray(get_object_vars($filaments));
        }elseif(is_array($filaments)) {
            $this->fillFromArray($filaments);
        }
    }

    private function fillFromArray(array $fill)
    {
        foreach ($fill as $key => $var) {
            $this->{$key} = $var;
        }
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

}