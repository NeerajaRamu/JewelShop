<?php

namespace App\DataTables\Filters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;


/**
 * Buttongroup filter class
 */
class ButtongroupFilter extends Filter
{
    public function __construct($builder, $options)
    {
        parent::__construct($builder, $options);

        if (is_null($this->getOption('strictSearch'))) {
            $this->setOption('strictSearch', true);
        }
    }

    /**
     * Datatables using Query Builder.
     *
     * @param \Illuminate\Database\Query\Builder $builder
     * @return \Illuminate\Database\Query\Builder $builder
     */
    protected function usingQueryBuilder(QueryBuilder $builder)
    {
        $value = trim($this->getOption('value'));

        if (strlen($value)) {
            // eloquent filtering
            if ($this->getOption('noClosure') && $this->getOption('callback')) {
                $this->applyCallback($this->getOption('callback'), $builder, $value);
            } else {
                $builder->where(function ($query) {
                    if ($this->getOption('callback')) {
                        $this->applyCallback($this->getOption('callback'), $query, $value);
                    } else {
                        $query->where($this->getOption('column'), 'like', $this->prepareSearchString($value));
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
     * @return \Illuminate\Support\Collection $builder
     */
    protected function usingCollection(Collection $builder)
    {
        $value = trim($this->getOption('value'));

        if (strlen($value)) {
            // collection filtering
            if ($this->getOption('callback')) {
                $builder = $this->applyCallback($this->getOption('callback'), $builder, $this->getOption('value'));
            } else {
                $builder = $builder->filter(function ($item) {
                    $columnValue = is_array($item) ? $item[$this->getOption('column')] : $item->{$this->getOption('column')};
                    if (preg_match($this->prepareSearchString($this->getOption('value')), $columnValue)) {
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
        if (!is_null($this->requestOptions) and strlen(trim($this->requestOptions))) {
            $requestAttributes = ['value' => $this->requestOptions];
            foreach ($this->options['buttons'] as $key => $option) {
                unset($this->options['buttons'][$key]['active']);
                if ($option['value'] == $this->requestOptions) {
                    $this->options['buttons'][$key]['active'] = true;
                }
            }
        }

        $this->options = array_merge($this->options, $requestAttributes);

        return $this->options;
    }
}
