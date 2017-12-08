<?php

namespace App\DataTables\Filters;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;

/**
 * Daterange filter class
 */
class DaterangeFilter extends Filter
{

    public function __construct($builder, $options)
    {
        parent::__construct($builder, $options);

        $this->setOption(
            'from',
            $this->prepareDate(
                $this->getOption('from'),
                $this->getOption('timezone'),
                $this->getOption('dbColumnTimezone')
            )
        );
        $this->setOption(
            'to',
            $this->prepareDate(
                $this->getOption('to'),
                $this->getOption('timezone'),
                $this->getOption('dbColumnTimezone')
            )
        );
    }

    /**
     * Datatables using Query Builder.
     *
     * @param \Illuminate\Database\Query\Builder $builder
     * @return \Illuminate\Database\Query\Builder $builder
     */
    protected function usingQueryBuilder(QueryBuilder $builder)
    {
        $from = $this->getOption('from')->toDateTimeString();
        $to   = $this->getOption('to')->toDateTimeString();

        // eloquent filtering
        if ($this->getOption('noClosure') && $this->getOption('callback')) {
            $this->applyCallback($this->getOption('callback'), $builder, $from, $to);
        } else {
            $builder->where(function ($query) use ($from, $to) {
                if ($this->getOption('callback')) {
                    $this->applyCallback($this->getOption('callback'), $query, $from, $to);
                } else {
                    $query->where($this->getOption('column'), '>=', $from)
                          ->where($this->getOption('column'), '<=', $to);
                }
            });
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
        $from = $this->getOption('from')->timestamp;
        $to   = $this->getOption('to')->timestamp;

        // collection filtering
        if ($this->getOption('callback')) {
            $builder = $this->applyCallback($this->getOption('callback'), $builder, $from, $to);
        } else {
            $builder = $builder->filter(function ($item) use ($from, $to) {
                $rawItemColumnValue = is_array($item) ? $item[$this->getOption('column')] : $item->{$this->getOption('column')};
                $itemColumnValue    = Carbon::parse($rawItemColumnValue)->setTimezone('UTC')->timestamp;
                if (($itemColumnValue >= $from) and ($itemColumnValue <= $to)) {
                    return $item;
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
            $requestAttributes['from'] = $this->prepareDate(array_get($this->requestOptions, 'from'));
        }

        if (array_get($this->requestOptions, 'to', null)) {
            $requestAttributes['to'] = $this->prepareDate(array_get($this->requestOptions, 'to'));
        }

        $this->options = array_merge($this->options, $requestAttributes);

        return $this->options;
    }

    /**
     * Make Carbon objects from dates
     * @param  string $timezone         timezone
     * @param  string $reportTimezone   report/filter timezone
     * @param  string $dbColumnTimezone db column timezone
     */
    protected function prepareDate($value, $reportTimezone = 'UTC', $dbColumnTimezone = 'UTC')
    {
        if (!($value instanceof Carbon)) {
            $value = Carbon::parse($value, $reportTimezone);
        }

        // convert date to db timezone to be able to search by this date
        $value->tz($dbColumnTimezone);

        return $value;
    }
}
