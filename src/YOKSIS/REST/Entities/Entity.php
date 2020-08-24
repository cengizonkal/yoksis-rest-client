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
        }
        if (is_string($filaments)) {
            $items = explode(',', $filaments);
            $fill = [];
            foreach ($items as $item) {
                $item = explode(':', $item);
                if (count($item) == 2) {
                    $fill[$item[0]] = $item[1];
                }
            }
            $this->fillFromArray($fill);
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