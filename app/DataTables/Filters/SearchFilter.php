<?php

namespace App\DataTables\Filters;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * DataTable filter class
 */
class SearchFilter extends Filter
{
    public function __construct($builder, $options)
    {
        parent::__construct($builder, $options);

        if (is_null($this->getOption('strictSearch'))) {
            $this->setOption('strictSearch', false);
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
        $keyword = trim($this->getOption('keyword'));

        if (strlen($keyword)) {
            // eloquent filtering
            if ($this->getOption('noClosure') && $this->getOption('callback')) {
                $this->applyCallback($this->getOption('callback'), $builder, $keyword);
            } else {
                $builder->where(function ($query) use ($keyword) {
                    if ($this->getOption('callback')) {
                        $this->applyCallback($this->getOption('callback'), $query, $keyword);
                    } else {
                        if ($this->getOption('strictSearch')) {
                            $query->where($this->getOption('column'), 'like', $keyword);
                        } else {
                            $keywords = explode(' ', $keyword);
                            foreach ($keywords as $kw) {
                                if (!empty($kw)) {
                                    $query->where($this->getOption('column'), 'like',
                                        $this->prepareSearchString($kw, true));
                                }
                            }
                        }

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
     * @return \Yajra\Datatables\Engines\CollectionEngine
     */
    protected function usingCollection(Collection $builder)
    {
        $keyword = trim($this->getOption('keyword'));

        if (strlen($keyword)) {
            // collection filtering
            if ($this->getOption('callback')) {
                $builder = $this->applyCallback($this->getOption('callback'), $builder, $keyword);
            } else {
                $builder = $builder->filter(function ($item) use ($keyword) {
                    $columnValue = is_array($item) ? $item[$this->getOption('column')] : $item->{$this->getOption('column')};
                    if ($this->getOption('strictSearch')) {
                        if (preg_match($keyword, $columnValue)) {
                            return $item;
                        }
                    } else {
                        $keywords = explode(' ', $keyword);
                        foreach ($keywords as $kw) {
                            if (!empty($kw)) {
                                if (!preg_match($this->prepareSearchString($kw, true), $columnValue)) {
                                    // row does not match keyword
                                    return false;
                                }
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
        if (!is_null($this->requestOptions) and strlen(trim($this->requestOptions))) {
            $requestAttributes = ['keyword' => $this->requestOptions];
        }

        $this->options = array_merge($this->options, $requestAttributes);

        return $this->options;
    }
}
