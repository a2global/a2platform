<?php


namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;


class ArrayUtility
{
    static public function prefixToKey(array $array, string $prefix)
    {
        $result = [];

        foreach ($array as $key => $value){
            $result[sprintf('%s%s', $prefix, $key)] = $value;
        }

        return $result;
    }
}