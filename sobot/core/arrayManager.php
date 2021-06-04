<?php
namespace sobot\core;

class arrayManager
{
    public function getSingleArray(...$array)
    {
        $res = [];
        for ($i = 0  ; $i < count($array) - 1 ; $i++) {
            $res = array_unique(array_merge($array[$i], $array[$i+1]), SORT_REGULAR);
        }
        return $res;
    }
}
