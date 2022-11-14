<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Builder;

class DataFilterHashBuilder
{
    public static function build(array $filters)
    {
        return md5(serialize($filters));
    }
}