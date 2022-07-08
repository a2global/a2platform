<?php

namespace A2Global\A2Platform\Bundle\DatasheetBundle\Builder;

use A2Global\A2Platform\Bundle\CoreBundle\Utility\QueryBuilderUtility;
use A2Global\A2Platform\Bundle\DataBundle\Component\DataCollection;
use A2Global\A2Platform\Bundle\DataBundle\Reader\ArrayDataReader;
use A2Global\A2Platform\Bundle\DataBundle\Reader\DataReaderInterface;
use A2Global\A2Platform\Bundle\DataBundle\Reader\QueryBuilderDataReader;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\Column\StringColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetColumn;
use A2Global\A2Platform\Bundle\DatasheetBundle\Component\DatasheetExposed;
use A2Global\A2Platform\Bundle\DatasheetBundle\Datasheet;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\ColumnProvider;
use A2Global\A2Platform\Bundle\DatasheetBundle\Provider\FilterProvider;
use Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class DatasheetBuilder
{
    public function __construct(
        protected $dataReaders,
        protected FilterProvider $filterProvider,
        protected RequestStack $requestStack,
        protected ColumnProvider $columnProvider,
    ) {
    }

    /**
     * This step are described in readme.md!
     *
     * @param Datasheet $datasheet
     * @return DatasheetExposed
     * @throws Exception
     */
    public function build(Datasheet $datasheet): DatasheetExposed
    {
        $config = $datasheet();

        // Exposing
        $datasheet = $this->expose($datasheet, $config);

        // Get data reader
        $dataReader = $this->getDatareader($datasheet, $config);

        // Filters
        $this->addFiltersToDataReader($datasheet, $dataReader);

        // Get data
        $dataCollection = $this->getData($dataReader);

        // Get columns
        $columns = $this->getColumns($dataCollection, $dataReader, $config);

        // Modify columns
        $columns = $this->showAddUpdateHideColumns($columns, $config);

        // Typify
        $columns = $this->typifyColumns($columns);

        // Adding data
        $datasheet->setData($dataCollection);

        // Adding columns
        foreach ($columns as $column) {
            $datasheet->addColumn($column);
        }

        // Adding additional data
        $request = $this->requestStack->getCurrentRequest();
        $queryParams = FilterProvider::decapsulate($request->query, $datasheet->getId()) ?? [];
        $datasheet->setQueryParameters($queryParams);

        return $datasheet;
    }

    protected function expose(Datasheet $datasheet, $config): DatasheetExposed
    {
        $datasheet = new DatasheetExposed();
        $datasheet->setId($config['id'] ?? substr(md5($config['invokedAt']), 0, 5));

        if (isset($config['title'])) {
            $datasheet->setTitle($config['title']);
        }

        return $datasheet;
    }

    protected function getDatareader(DatasheetExposed $datasheet, $config)
    {
        $dataReader = $this->findSuitableDataReader($config['dataSource']);
        $dataReader->setSource($config['dataSource']);

        return $dataReader;
    }

    protected function findSuitableDataReader($source): DataReaderInterface
    {
        /** @var DataReaderInterface $dataReader */
        foreach ($this->dataReaders as $dataReader) {
            if ($dataReader->supports($source)) {
                return $dataReader;
            }
        }
    }

    protected function addFiltersToDataReader(DatasheetExposed $datasheet, DataReaderInterface $dataReader)
    {
        foreach ($this->filterProvider->getFilters($datasheet->getId()) as $filter) {
            $dataReader->addFilter($filter);
        }
    }

    protected function getData(DataReaderInterface $dataReader): DataCollection
    {
        return $dataReader->getData();
    }

    protected function getColumns(DataCollection $dataCollection, DataReaderInterface $dataReader, array $config)
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
                $columns[$field['name']] = $this->findSupportedColumn($field);
            }
        }

        return $columns;
    }

    protected function findSupportedColumn($field)
    {
        if (!$field['typeResolved']) {
            throw new Exception('Unresolved data type: ' . $field['type']);
        }

        foreach ($this->columnProvider->get() as $column) {
            if ($column::supportsDataType($field['typeResolved'])) {
                return new $column($field['name']);
            }
        }

        throw new Exception('Unsupported data type: ' . $field['typeResolved']);
    }

    protected function showAddUpdateHideColumns(array $columns, array $config): array
    {
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

        return $columns;
    }

    protected function typifyColumns($columns)
    {
        $typifiedColumns = [];

        /** @var DatasheetColumn $column */
        foreach ($columns as $column) {

            // If its already typed column
            if (get_class($column) !== DatasheetColumn::class) {
                $typifiedColumns[] = $column;

                continue;
            }
            $type = $column->getType() ?? StringColumn::class;
            $typedColumn = new $type($column->getName());

            foreach (['position', 'title', 'width', 'align'] as $parameters) {
                if (!is_null($column->{'get' . $parameters}())) {
                    $typedColumn->{'set' . $parameters}($column->{'get' . $parameters}());
                }
            }
            $typifiedColumns[] = $typedColumn;
        }

        return $typifiedColumns;
    }
}