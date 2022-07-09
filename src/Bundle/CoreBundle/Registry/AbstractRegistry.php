<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Registry;

abstract class AbstractRegistry
{
    public function __construct(
        protected $services
    ) {
    }

    public function get()
    {
        return $this->services;
    }
}