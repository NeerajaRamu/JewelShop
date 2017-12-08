<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * DataTable filter class
 */
class DatatableFilter
{
    protected $request;
    protected $records;

    /**
     * Filter constructor.
     * @param mixed $records
     */
    public function __construct($request, $records = [])
    {
        $this->request = $request;
        $this->records = $records;
    }

    /**
     * Processing filter data
     * @param  array $defaultFiltersOptions initial filter options
     */
    public function process($defaultFiltersOptions = [])
    {
        foreach ($this->request->get('filters', $defaultFiltersOptions) as $options) {

            $options = $this->applyDefaultOptions($options, $defaultFiltersOptions);

            $type = ucfirst($options['type']);

            // call filter processor class
            $filterProcessor = "{$type}Filter";
            $this->apply('App\DataTables\Filters\\' . $filterProcessor, $options);
        }

        return $this->records;
    }

    /**
     * Apply filter class to records
     * @param  string $className filter class name
     * @param  array  $options filter options
     */
    public function apply($className, array $options)
    {
        $this->records = (new $className($this->records, $options))->apply();
    }

    /**
     * Apply default options
     * @param  array $options filter options
     * @param  array $defaultFiltersOptions default filter options
     * @return array                         filter options
     */
    public function applyDefaultOptions($options, $defaultFiltersOptions)
    {
        foreach ($defaultFiltersOptions as $defaultOptions) {
            if (($defaultOptions['type'] == $options['type']) and ($defaultOptions['column'] == $options['column'])) {
                $options           = array_merge($defaultOptions, $options);
                $options['engine'] = $this->prepareEngineClass($options['engine']);
            }
        }
        return $options;
    }

    /**
     * Prepare engine classes
     * @param  string $className records object class name
     */
    protected function prepareEngineClass($className)
    {
        switch ($className) {
            case 'Collection':
                return Collection::class;
                break;

            default:
                // QueryBuilder
                return QueryBuilder::class;
                break;
        }
    }

    /**
     * Get filter options from URL
     * @param  aray $filterOptions filter options
     * @return array               filter options with a requested in URL parameters
     */
    public function getFilterOptionsFromURL($filterOptions)
    {
        $filterData = [];
        foreach ($filterOptions as $options) {

            $type = ucfirst($options['type']);

            // call filter processor class
            $filterClass  = "{$type}Filter";
            $filterData[] = $this->grabUrlData('App\DataTables\Filters\\' . $filterClass, $options);
        }

        return $filterData;
    }

    /**
     * Grab filter attributes from URL
     * @param  string $className filter class name
     * @param  array  $options filter options
     */
    protected function grabUrlData($className, array $options)
    {
        return (new $className($this->records, $options))->grabAttributesFromURL();
    }
}
