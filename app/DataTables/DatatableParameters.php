<?php

namespace App\DataTables;

use Carbon\Carbon;
use Yajra\Datatables\Request;

/**
 * DataTable order class
 */
class DatatableParameters
{
    protected $request;

    /**
     * Filter constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request->request->count() ? $request : Request::capture();
    }

    /**
     * grab ordering from URL
     *
     * @return array list of ordering
     */
    public function grabOrderingFromRequest()
    {
        if ($this->request->has('ordering')) {
            $orderData = $this->request->get('ordering', []);

            $ordering = [];
            foreach ($orderData as $column => $value) {
                $ordering[] = [$column, $value];
            }

            return $ordering;
        }
        return false;
    }

    /**
     * Grab page_length from URL
     *
     * @return string number of records per page
     */
    public function grabPageLengthFromRequest()
    {
        if ($this->request->has('page_length')) {
            return $this->request->get('page_length', '');
        }
        return false;
    }

    /**
     * Grab display_start from URL
     *
     * @return string start position of displaying data
     */
    public function grabDisplayStartFromRequest()
    {
        if ($this->request->has('display_start')) {
            return $this->request->get('display_start', '');
        }
        return false;
    }

}
