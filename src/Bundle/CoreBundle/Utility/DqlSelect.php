<?php

namespace A2Global\A2Platform\Bundle\CoreBundle\Utility;

class DqlSelect
{
    protected $aliasName;

    protected $sourcePath;

    protected $tableAlias;

    protected $fieldName;

    public function __construct(string $rawSelect)
    {
        $data = [];

        if (preg_match("/\sAS\s(.+)$/ius", $rawSelect, $result)) {
            $this->aliasName = $result[1];
        }

        if (preg_match("/CONCAT\((.+)\)/ius", $rawSelect, $result)) {
            $paramsWithoutStrings = preg_replace("/'(.*?)'/", '', $result[1]);
            $params = explode(',', $paramsWithoutStrings);

            foreach ($params as $param) {
                if (trim($param)) {
                    $this->sourcePath = trim($param);
                }
            }
        } elseif (preg_match("/^([^\s]+)\s/ius", $rawSelect, $result)) {
            $this->sourcePath = $result[1];
        } else {
            $this->sourcePath = $rawSelect;
        }

        $result = explode('.', $this->sourcePath);

        $this->tableAlias = $result[0];
        $this->fieldName = $result[1];

        if (!$this->aliasName) {
            $this->aliasName = $result[1];
        }
    }

    public function getAliasName()
    {
        return $this->aliasName;
    }

    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    public function getTableAlias()
    {
        return $this->tableAlias;
    }

    public function getFieldName()
    {
        return $this->fieldName;
    }
}