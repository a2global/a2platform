<?php

namespace A2Global\A2Platform\Bundle\PlatformBundle\Builder\Data;

class DataFilterHashBuilder
{
    public static function build(array $filters)
    {
        return md5(serialize($filters));
    }
}