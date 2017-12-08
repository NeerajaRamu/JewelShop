<?php

namespace App\DataTables\Filters;

use Illuminate\Support\Collection;
use Yajra\Datatables\Engines\CollectionEngine;
use App\Extensions\Datatable\Engines\AppEloquentEngine;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Abstract class Filter
 *
 */
abstract class Filter
{
    protected $options;
    protected $builder;
    protected $request;
    protected $requestOptions;

    public function __construct($builder, $options)
    {
        $this->builder        = $builder;
        $this->options        = $options;
        $this->request        = request();
        $this->requestOptions = $this->request->get($this->getOption('column'), null);
    }

    /**
     * Gets option form filter options
     * @param  string $key filter option key
     * @param  string $defaultValue default filter option value
     * @return mixed                filter option value
     */
    public function getOption($key, $defaultValue = null)
    {
        return array_get($this->options, $key, $defaultValue);
    }

    /**
     * Set filter option
     * @param  string $key filter option key
     * @return mixed        filter option value
     */
    public function setOption($key, $value)
    {
        return array_set($this->options, $key, $value);
    }

    /**
     * Apply results.
     *
     * @return mixed
     */
    public function apply()
    {
        $engine = $this->getOption('engine');

        if (($this->builder instanceof QueryBuilder) and ($engine == QueryBuilder::class)) {
            return $this->usingQueryBuilder($this->builder);
        } else {
            if (($this->builder instanceof Collection) and ($engine == Collection::class)) {
                return $this->usingCollection($this->builder);
            } elseif (($this->builder instanceof CollectionEngine) and ($engine == Collection::class)) {
                return $this->usingCollection($this->builder->collection);
            } elseif (($this->builder instanceof AppEloquentEngine) and ($engine == QueryBuilder::class)) {

                return $this->usingEloquent($this->builder);
            }
        }

        return $this->builder;
    }


    /**
     * Apply filter callback if it's defined
     * @param  Closure $callback filter callback
     * @param  mixed $this ->records
     * @param  array $options filter options
     */
    protected function applyCallback()
    {
        $args = func_get_args();

        // first parameter should be always $callback
        // second parameter should be always $this->records

        $callback = $args[0];

        array_shift($args);

        if ($callback) {
            return call_user_func_array($callback, $args);
        }
    }

    /**
     *  Generates search string for strict or soft search
     *  strict means exactly "value"
     *  soft means contains "value"
     * @param  string $value search string
     * @param  boolean $isStrictSearch strict search boolean flag
     *
     * @return  string search string
     */
    protected function prepareSearchString($value, $forceSoftSearch = false)
    {
        if ($this->getOption('engine') == Collection::class) {
            if (!$this->getOption('strictSearch') or $forceSoftSearch) {
                $value = "/{$value}/i";
            } else {
                $value = "~\b$value\b~";
            }

        } elseif ($this->getOption('engine') == QueryBuilder::class) {
            if (!$this->getOption('strictSearch') or $forceSoftSearch) {
                $value = "%$value%";
            }
        }

        return $value;
    }

    /**
     * Get filter attributes from URL and merge it with a default set
     *
     * @return array filter options
     */
    abstract public function grabAttributesFromURL();

    /**
     * Datatables using Query Builder.
     *
     * @param \Illuminate\Database\Query\Builder $builder
     * @return \Yajra\Datatables\Engines\QueryBuilderEngine
     */
    abstract protected function usingQueryBuilder(QueryBuilder $builder);


    /**
     * Datatables using Collection.
     *
     * @param \Illuminate\Support\Collection $builder
     * @return \Yajra\Datatables\Engines\CollectionEngine
     */
    abstract protected function usingCollection(Collection $builder);

    /**
     * Datatables using Eloquent.
     *
     * @param  mixed $builder
     * @return \Yajra\Datatables\Engines\EloquentEngine
     */
    protected function usingEloquent($builder)
    {
        $builder = $builder instanceof QueryBuilder ? $builder : $builder->getQueryBuilder();

        $this->usingQueryBuilder($builder);
    }

}
