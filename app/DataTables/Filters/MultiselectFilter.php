<?php

namespace App\DataTables\Filters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Multiselect filter class
 */
class MultiselectFilter extends Filter
{
    public function __construct($builder, $options)
    {
        parent::__construct($builder, $options);

        if (is_null($this->getOption('matchAllValues'))) {
            $this->setOption('matchAllValues', true);
        }

    }

    /**
     * Datatables using Query Builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function usingQueryBuilder(QueryBuilder $builder)
    {
        $values = trim($this->getOption('value'));

        if (!empty($values)) {
            // eloquent filtering
            if ($this->getOption('callback')) {
                $builder = $this->applyCallback($this->getOption('callback'), $builder, $values);
            } else {
                $builder->where(function ($query) use ($values) {
                    $values      = explode(',', $values) ?: [];
                    $column      = $this->getOption('column');
                    $whereMethod = !$this->getOption('matchAllValues') ? 'orWhere' : 'where';
                    foreach ($values as $value) {
                        $query->{$whereMethod}($column, $value);
                    }
                });
            }
        }
        return $builder;
    }

    /**
     * Datatables using Collection.
     *
     * @param \Illuminate\Support\Collection $builder
     * @return \Illuminate\Support\Collection
     */
    protected function usingCollection(Collection $builder)
    {
        $values = trim($this->getOption('value'));

        if (!empty($values)) {
            // collection filtering
            if ($this->getOption('callback')) {
                $builder = $this->applyCallback($this->getOption('callback'), $builder, $values);
            } else {
                $builder = $builder->filter(function ($item) use ($values) {
                    $values         = explode(',', $values) ?: [];
                    $column         = $this->getOption('column');
                    $columnValue    = is_array($item) ? $item[$column] : $item->{$column};
                    if (!$this->getOption('matchAllValues')) {
                        foreach ($values as $value) {
                            if ($columnValue == $value) {
                                return $item;
                            }
                        }
                        return false;
                    } else {
                        foreach ($values as $value) {
                            if ($columnValue != $value) {
                                return false;
                            }
                        }
                        return $item;
                    }
                });
            }
        }
        return $builder;
    }

    /**
     * Get filter attributes from URL and merge it with a default set
     *
     * @return array filter options
     */
    public function grabAttributesFromURL()
    {
        $requestAttributes = [];
        if (!is_null($this->requestOptions) && strlen($this->requestOptions)) {
            $requestAttributes = ['value' => $this->requestOptions];
            foreach (explode(',', $this->requestOptions) as $requestOption) {
                foreach ($this->getOption('options', []) as $key => $option) {
                    if ($option['value'] == $requestOption) {
                        $this->options['options'][$key]['active'] = true;
                    }
                }
            }
        }

        $this->options = array_merge($this->options, $requestAttributes);

        return $this->options;
    }
}
