<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\BooleanColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\DateTimeColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\NumberColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\TextColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\FilterProvider;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class DatasheetBuilder
{
    public function __construct(
        protected $dataReaders,
        protected FilterProvider $filterProvider,
        protected RequestStack $requestStack,
    ) {
    }

    public function build(Datasheet $datasheet): DatasheetExposed
    {
        $config = $datasheet();
        $datasheet = new DatasheetExposed();
        $datasheet->setId($config['id'] ?? substr(md5($config['invokedAt']), 0, 5));

        if (isset($config['title'])) {
            $datasheet->setTitle($config['title']);
        }
        $dataReader = $this->getSuitableDataReader($config['dataSource']);
        $dataReader->setSource($config['dataSource']);

        foreach ($this->filterProvider->getFilters($datasheet->getId()) as $filter) {
            $dataReader->addFilter($filter);
        }
        $dataCollection = $dataReader->getData();
        $columns = $this->buildColumns($dataCollection, $dataReader, $config);

        if ($config['columns']['show'] ?? false) {
            foreach ($columns as $fieldName => $column) {
                if (!in_array($fieldName, $config['columns']['show'])) {
                    unset($columns[$fieldName]);
                }
            }
        }

        /** @var DatasheetColumn $column */
        foreach ($config['columns']['add'] ?? [] as $column) {
            $columns[$column->getName()] = $column;
        }

        /** @var DatasheetColumn $column */
        foreach ($config['columns']['update'] ?? [] as $column) {
            $columns[$column->getName()] = $column;
        }

        /** @var DatasheetColumn $column */
        foreach ($config['columns']['hide'] ?? [] as $columnName) {
            unset($columns[$columnName]);
        }

        /** @var DatasheetColumn $column */
        foreach ($columns as $column) {
            if (get_class($column) !== DatasheetColumn::class) {
                $datasheet->addColumn($column);
                continue;
            }
            $type = $column->getType() ?? StringColumn::class;
            $typedColumn = new $type($column->getName());

            foreach (['position', 'title', 'width', 'align'] as $parameters) {
                if (!is_null($column->{'get' . $parameters}())) {
                    $typedColumn->{'set' . $parameters}($column->{'get' . $parameters}());
                }
            }
            $datasheet->addColumn($typedColumn);
        }

        $datasheet
            ->setData($dataCollection)
            ->setQueryParameters(
                FilterProvider::decapsulate(
                    $this->requestStack->getCurrentRequest()->query, $datasheet->getId()
                ) ?? []
            );

        return $datasheet;
    }

    protected function getSuitableDataReader($source): DataReaderInterface
    {
        /** @var DataReaderInterface $dataReader */
        foreach ($this->dataReaders as $dataReader) {
            if ($dataReader->supports($source)) {
                return $dataReader;
            }
        }
    }

    protected function buildColumns(DataCollection $dataCollection, DataReaderInterface $dataReader, array $config)
    {
        $columns = [];

        if ($dataReader instanceof ArrayDataReader) {
            foreach ($dataCollection->getFields() as $fieldName) {
                $columns[$fieldName] = new DatasheetColumn($fieldName);
            }
        }

        if ($dataReader instanceof QueryBuilderDataReader) {
            $fields = QueryBuilderUtility::getEntityFields(
                QueryBuilderUtility::getPrimaryClass($config['dataSource'])
            );

            foreach ($fields as $field) {
                switch ($field['type']) {
                    case 'integer':
                    case 'decimal':
                        $columns[$field['name']] = new NumberColumn($field['name']);
                        break;
                    case 'boolean':
                        $columns[$field['name']] = new BooleanColumn($field['name']);
                        break;
                    case 'string':
                    case 'json':
                        $columns[$field['name']] = new StringColumn($field['name']);
                        break;
                    case 'text':
                        $columns[$field['name']] = new TextColumn($field['name']);
                        break;
                    case 'date':
                    case 'datetime':
                        $columns[$field['name']] = new DateTimeColumn($field['name']);
                        break;
                    default:
                        throw new Exception('Unsupported field type: ' . $field['type']);
                }
            }
        }

        return $columns;
    }
}