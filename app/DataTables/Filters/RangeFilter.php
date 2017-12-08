<?php

namespace App\DataTables\Filters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Range filter class
 */
class RangeFilter extends Filter
{

    public function __construct($builder, $options)
    {
        parent::__construct($builder, $options);

        $this->setOption('from', $this->getOption('from'));
        $this->setOption('to', $this->getOption('to'));
    }

    /**
     * Datatables using Collection.
     *
     * @param \Illuminate\Support\Collection $builder
     * @return \Illuminate\Support\Collection $builder
     */
    protected function usingCollection(Collection $builder)
    {
        $from = $this->prepareData($this->getOption('from'));
        $to   = $this->prepareData($this->getOption('to'));
        
        // collection filtering
        if ($this->getOption('callback')) {
            $builder = $this->applyCallback($this->getOption('callback'), $builder, $from, $to);
        } else {
            $builder = $builder->filter(function ($item) use ($from, $to) {
                $columnValue = is_array($item) ? $item[$this->getOption('column')] : $item->{$this->getOption('column')};
                if (strlen($from) && strlen($to)) {
                    if ($columnValue >= $from && $columnValue <= $to) {
                        return $item;
                    }
                } else {
                    if (strlen($from) && $columnValue >= $from) {
                        return $item;
                    }
                    if (strlen($to) && $columnValue <= $to) {
                        return $item;
                    }
                }
            });
        }

        return $builder;
    }

    /**
     * Datatables using Query Builder.
     *
     * @param \Illuminate\Database\Query\Builder $builder
     * @return \Illuminate\Database\Query\Builder $builder
     */
    protected function usingQueryBuilder(QueryBuilder $builder)
    {
        $from = $this->prepareData($this->getOption('from'));
        $to   = $this->prepareData($this->getOption('to'));

        // eloquent filtering
        if ($this->getOption('noClosure') && $this->getOption('callback')) {
            $this->applyCallback($this->getOption('callback'), $builder, $from, $to);
        } else {
            $builder->where(function ($query) use ($from, $to) {
                if ($this->getOption('callback')) {
                    $this->applyCallback($this->getOption('callback'), $query, $from, $to);
                } else {
                    if (strlen($from)) {
                        $query->where($this->getOption('column'), '>=', $from);
                    }
                    if (strlen($to)) {
                        $query->where($this->getOption('column'), '<=', $to);
                    }
                }
            });
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

        if (array_get($this->requestOptions, 'from', null)) {
            $requestAttributes['from'] = array_get($this->requestOptions, 'from');
        }

        if (array_get($this->requestOptions, 'to', null)) {
            $requestAttributes['to'] = array_get($this->requestOptions, 'to');
        }

        $this->options = array_merge($this->options, $requestAttributes);

        return $this->options;
    }

    /**
     * Change comma to dot
     * @param $value
     */
    public function prepareData($value)
    {
        if (strpos($value, ',') == true) {
            return str_replace(',', '', $value);
        }

        return $value;
    }
}