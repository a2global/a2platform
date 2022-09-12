<?php

namespace A2Global\A2Platform\Bundle\DataBundle\Component;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\StringUtility;
use Exception;

class DataItem
{
    public function __construct(
        protected $data
    ) {
    }

    public function getValue(string $field)
    {
        if (is_array($this->data)) {
            return $this->data[$field];
        }

        if (is_object($this->data)) {
            return $this->getObjectValue($field);
        }
    }

    /** @codeCoverageIgnore  */
    protected function getObjectValue($field)
    {
        foreach (['', 'get', 'is', 'has'] as $prefix) {
            $method = $prefix . StringUtility::toPascalCase($field);

            if (method_exists($this->data, $method)) {
                return $this->data->{$method}();
            }
        }

        throw new Exception(
            sprintf(
                'Failed to get data %s from %s via get/is/has+%s',
                $field,
                get_class($this->data),
                StringUtility::toPascalCase($field)
            )
        );
    }
}