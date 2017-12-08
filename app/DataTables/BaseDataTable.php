<?php

namespace App\DataTables;

use App\Extensions\Datatable\AppDatatables;
use App\Extensions\Datatable\Html\AppBuilder;
use App\Extensions\Datatable\Transformers\AppDataTransformer;
use App\Exceptions\DataTables\ExportingLimitException;
use App\Repositories\DeploymentRepository;
use Barryvdh\Snappy\PdfWrapper;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
//use Yajra\Datatables\Services\DataTable;
use Yajra\DataTables\Services\DataTable;

class BaseDataTable extends DataTable
{
    protected $records;
    protected $ajaxClosure;
    protected $htmlClosure;
    protected $makeClosure;
    protected $filters                  = [];
    protected $timezone;
    protected $query;
    protected $hasCollectionFilters     = false;
    protected $forceCollectionFiltering = false;
    protected $exportDatetime;
    protected $length;
    protected $totalRecords;
    protected $recordsFiltered;
    protected $extraDataCallback;
    protected $exportPDFArgs;
    protected $exportRecordsLimit;
    protected $currentRequest;
    protected $exportTitle;

    /**
     * DataTable constructor.
     *
     * @param null|\Closure $ajaxClosure
     * @param null|\Closure $htmlClosure
     * @param null|String   $timezone
     * @param null|\Closure $makeClosure overrides the make method defined in BaseEngine
     */
    public function __construct($ajaxClosure = null, $htmlClosure = null, $timezone = null, $makeClosure = null)
    {
        $datatables        = app(AppDatatables::class);
        $viewFactory       = app(Factory::class);
        $this->ajaxClosure = $ajaxClosure;
        $this->htmlClosure = $htmlClosure;
        $this->useTimezone($timezone);
        $this->makeClosure = $makeClosure;

        // set default PDF export arguments
        $this->exportPDFArgs = [
            'A4', // paper format
            'landscape', // paper orientation
            '1' // page zoom factor
        ];

        // set maximum records to export. All by default (-1)
        $this->exportRecordsLimit = -1;

        parent::__construct($datatables, $viewFactory);

        $this->currentRequest = $this->request();
        $this->setExportDatetime();
    }

    /**
     * Extend parent render method to accept records Collection/Eloquent/Query Builder object
     *
     * @param string $view
     * @param array  $data
     * @param array  $mergeData
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function render($view, $records = [], $data = [], $mergeData = [])
    {
        if (!empty($records)) {
            $this->records = $records;
        }

        if ($this->currentRequest->ajax() && $this->currentRequest->wantsJson()) {
            // returns json data in case of ajax request
            return $this->ajax();
        }

        try {
            $action = $this->currentRequest->get('action');
            // check if action parameter present and equal to 'print' or 'csv' or 'excel' or 'pdf'
            if ($action && in_array($action, ['print', 'csv', 'excel', 'pdf'])) {
                if ($action == 'print') {
                    return $this->printPreview();
                }

                return call_user_func_array([$this, $action], []);
            }
        } catch (Exception $e) {
            if ($e instanceof ExportingLimitException) {
                $error = $this->getExportingLimitError();
            } else {
                $error = $e->getMessage();
            }
            // redirect back and show an error
            return redirect()->back()
                             ->with('error', $error);
        }

        // returns datatable data for the initial page load
        return $this->viewFactory->make($view, $data, $mergeData)->with('dataTable', $this->html());
    }

    /**
     * Create exporting limit error message
     * @return string
     */
    public function getExportingLimitError()
    {
        $currentAction = '';
        if ($this->currentRequest->get('action')) {
            $currentAction = 'to ' . strtoupper($this->currentRequest->get('action'));
        }

        return "Your export {$currentAction} has surpassed the {$this->exportRecordsLimit} row limit of this report. Please refine the filters and try again.";
    }

    /**
     * Display printable view of datatables.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function printPreview()
    {
        // retrieve data for export
        $exportData = $this->getDataForPrint();

        // render html based on a given template and datatable information
        return $this->viewFactory->make(
            $this->printPreview,
            $exportData
        );
    }

    /**
     * Check exporting limit
     * Note: filter conditions should be applied to query before execution this method
     * @return \Redirect
     */
    protected function isExportingLimitReached()
    {
        // count number of records for the current query
        $recordsCount = $this->query()->count();

        // check if user try to export more records than we allow
        if (($this->exportRecordsLimit > 0) && ($recordsCount > $this->exportRecordsLimit)) {
            return true;
        }

        return false;
    }

    /**
     * Returns PDF file with the given data
     * @return mixed PDF file
     */
    public function pdf()
    {
        /**
         * @var \Barryvdh\Snappy\PdfWrapper $snappy
         */
        $snappy = app(PdfWrapper::class);

        $options = config('datatables.snappy.options', [
            'no-outline'    => true,
            'margin-left'   => '10mm',
            'margin-right'  => '10mm',
            'margin-top'    => '10mm',
            'margin-bottom' => '10mm',
        ]);

        $snappy->snappy()->setTimeout(30);

        // Apply additional export options
        $paperSize   = $this->exportPDFArgs[0];
        $orientation = $this->exportPDFArgs[1];
        $zoomFactor  = $this->exportPDFArgs[2];

        $options = array_merge(
            $options,
            [
                'page-size' => $paperSize,
                'zoom'      => $zoomFactor,
            ]
        );

        $snappy->setOptions($options)
               ->setOrientation($orientation);

        return $snappy->loadHTML($this->printPreview())
                      ->download($this->getFilename() . ".pdf");
    }

    /**
     * Get mapped columns versus final decorated output.
     *
     * @return array
     */
    protected function getDataForPrint()
    {
        $columns = $this->printColumns();

        // changed it from `printable` to `exportable` to be able to export it correctly
        $data = $this->mapResponseToColumns($columns, 'exportable');

        // get filters from request
        $requestFilterData = $this->currentRequest->get('filters', []);
        $datatableFilters  = [];

        if (!empty($requestFilterData)) {
            // prepare filter data besaed on defined filters
            foreach ($requestFilterData as $requestFilter) {
                $datatableFilters[] = (new DatatableFilter($this->currentRequest))
                    ->applyDefaultOptions($requestFilter, $this->filters);
            }
        } else {
            // get defualt filter data defined in conroller
            $datatableFilters = $this->filters;
        }

        // prepare meta information for the export file
        $meta                    = [];
        $meta['filters']         = $datatableFilters;
        $meta['title']           = !empty($this->exportTitle) ? $this->exportTitle : (!empty($this->filename) ? $this->filename : '');
        $meta['datetime']        = $this->exportDatetime;
        $meta['timezone']        = $this->timezone ? $this->timezone : 'UTC';
        $meta['recordsTotal']    = $this->recordsTotal;
        $meta['recordsFiltered'] = $this->recordsFiltered;
        $meta['pageLength']      = $this->pageLength;

        return compact(['data', 'datatableFilters', 'meta', 'columns']);
    }

    /**
     * Map ajax response to columns definition.
     *
     * @param mixed  $columns
     * @param string $type
     * @return array
     */
    protected function mapResponseToColumns($columns, $type)
    {
        return array_map(function ($row) use ($columns, $type) {
            if ($columns) {
                return (new AppDataTransformer())->transform($row, $columns, $type);
            }

            return $row;
        }, $this->getExportAjaxResponseData());
    }

    /**
     * Display ajax response.
     *
     * Prepare query based on a selected filters
     * Sets timezone to
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $ajax = $this->prepareAjaxData();

        if (is_callable($this->makeClosure)) {
            // apply makeClosure if it was defined on BaseDataTable class initializaiton
            return call_user_func($this->makeClosure, $ajax, $this);
        }

        // returns compiled json data
        return $ajax->make(true);
    }

    /**
     * Prepare ajax query data
     * @return mixed ajax engine instance
     */
    protected function prepareAjaxData()
    {
        $ajax = $this->datatables->of($this->query(), $this->currentRequest);

        if (is_callable($this->ajaxClosure)) {
            // apply ajaxClosure if $this->processAjaxData() was defined
            // $dataTable = new BaseDataTable(
            //     $this->processAjaxData(),
            //     $this->processHtmlBuilderData()
            // );
            $ajax = call_user_func($this->ajaxClosure, $ajax, $this);
        }

        // process filters to modify db query wiht the filter condition
        $ajax->processFilters($this->currentRequest, $ajax, $this->filters);

        $ajax->setTimezone($this->timezone);

        if (is_callable($this->extraDataCallback)) {
            // apply extra data to the main ajax data
            $ajax = call_user_func($this->extraDataCallback, $ajax);
        }

        return $ajax;
    }

    /**
     * Get decorated data for export as defined in datatables ajax response.
     *
     * @return array
     * @throws ExportingLimitException
     */
    protected function getExportAjaxResponseData()
    {
        // add ability to control maximum length of records to export
        $this->datatables->getRequest()->merge(['length' => $this->exportRecordsLimit]);

        // prepare ajax data
        $ajax = $this->prepareAjaxData();

        // set a list of columns that should be excluded
        $ajax->setExcludedColumnsTransformerOptions($this->getNonExportableColumns());

        if ($this->isExportingLimitReached()) {
            throw new ExportingLimitException();
        }

        if (is_callable($this->makeClosure)) {
            // apply makeClosure if it was defined on BaseDataTable class initializaiton
            $response = call_user_func($this->makeClosure, $ajax, $this);
        } else {
            // compile data to json
            $response = $ajax->make(true);
        }

        // get json data
        $data = $response->getData(true);

        // add meta information to json data
        $this->pageLength      = array_get($data, 'input.length', 10);
        $this->recordsTotal    = array_get($data, 'recordsTotal', 0);
        $this->recordsFiltered = array_get($data, 'recordsFiltered', 0);

        return $data['data'];
    }

    /**
     * Get list of non exportabe columns
     *
     * @return array  non-exportable columns
     */
    protected function getNonExportableColumns()
    {
        // list of columns
        $columns  = $this->getColumnsFromBuilder();
        $filtered = $columns->where('exportable', false)
                            ->pluck('name');

        return $filtered->all();
    }

    /**
     * Allows custom information to be added to a datatable response with a
     * ajax data processor callback
     *
     * @param $extraCallback
     */
    public function addExtraData($extraCallback)
    {
        $this->extraDataCallback = $extraCallback;
    }

    /**
     * Get the query object to be processed by datatables.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        if ($this->hasCollectionFilters and $this->forceCollectionFiltering and !($this->records instanceof Collection)) {
            // uses for filtering by query builder
            // put filter conditions to db query if such filters was defined
            $this->prepareQuery($this->records);

            // perform db query and return collection of records
            return collect($this->records->get());
        }

        // returns \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
        return $this->records;
    }

    /**
     * Optional method if you want to use html builder
     *
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        // prepare builder with the initial parameters and attributes
        $builder = $this->builder()
                        ->parameters($this->getBuilderParameters())
                        ->setDefaultTableAttributes($this->getDefaultTableAttributes());

        if (is_callable($this->htmlClosure)) {
            // apply processHtmlBuilderData() if it was defined
            // $dataTable = new BaseDataTable(
            //    $this->processAjaxData(),
            //    $this->processHtmlBuilderData()
            // );
            call_user_func($this->htmlClosure, $builder);
        } else {
            // default configs
            $builder->columns($this->getColumns());
            $builder->ajax('')
                    ->parameters($this->getBuilderParameters());
        }

        return $builder;
    }

    /**
     * set exportTitle class property
     *
     * @param $value value to set to exportTitle
     *
     * @return $this
     */
    public function setExportTitle($value)
    {
        $this->exportTitle = $value;

        return $this;
    }

    /**
     * Get export filename.
     *
     * @return string
     */
    public function getFilename()
    {
        $filename = preg_replace(
            '/[^\w]*/i',
            '',
            !empty($this->filename) ? $this->filename : (!empty($this->exportTitle) ? $this->exportTitle : '')
        );

        if (!empty($filename)) {
            return $filename . '_' . $this->exportDatetime->copy()->tz('UTC')->format('YmdHis');
        }

        return $this->filename();
    }

    /**
     * Set filename for export file.
     *
     * @return string
     */
    public function setExportFilename($value)
    {
        $this->filename = $value;

        return $this;
    }

    /**
     * Set export datetime to current time in current Deployment's default_timezone
     *
     * @return string
     */
    public function setExportDatetime()
    {
        $this->exportDatetime = Carbon::now(app(DeploymentRepository::class)->current()->default_timezone);

        return $this;
    }

    /**
     * Force use collection filtering.
     *
     * @return string
     */
    public function setForceCollectionFiltering($value)
    {
        $this->forceCollectionFiltering = $value;

        return $this;
    }

    /**
     * Set printPreview.
     *
     * @return string
     */
    public function setPrintPreview($value)
    {
        $this->printPreview = $value;
    }

    /**
     * get default builder parameters
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'buttons'      => [
                'csv',
                'excel',
                'pdf',
                'reload',
            ],
            'dom'          => '<"html5buttons"B>liprtip',
            'initComplete' => 'datatableInitComplete',
        ];
    }

    /**
     * get default table attributes
     *
     * @return array
     */
    protected function getDefaultTableAttributes()
    {
        return [
            'class' => 'datatable table-bordered table-hover table-striped',
            'width' => '100%',
        ];
    }

    /**
     * Get default columns.
     * Example of column values
     *  [
     *      'defaultContent' => '',
     *      'title'          => '',
     *      'data'           => '',
     *      'name'           => '',
     *      'orderable'      => false,
     *      'searchable'     => false,
     *      'exportable'     => false,
     *      'printable'      => true,
     *      'width'          => '10px'
     *  ]
     * @return array
     */
    private function getColumns()
    {
        return [
            'id'         => ['title' => 'ID'],
            'created_at' => ['title' => 'Created At'],
            'updated_at' => ['title' => 'Updated At'],
        ];
    }

    /**
     * Globally shares variable $force_use_timezone to views
     *
     * @param  $timezone timezone name
     * @return self
     */
    public function useTimezone($timezone)
    {
        $this->timezone = $timezone;
        view()->share('force_use_timezone', $timezone);

        return $this;
    }

    /**
     * Get timezone
     * @return string timezone
     */
    public function getTimezone()
    {
        return $this->timezone ? $this->timezone : 'UTC';
    }

    /**
     * Format column to defined timezone
     * @param  string $date date time
     * @param  string $format date time format
     * @return string         formated date time
     */
    public function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        $timezone = $this->getTimezone();

        return parseToFormat($date, $timezone, $format);
    }

    /**
     * Set PDF Export arguments
     *
     * @param array $args PDF export arguments
     *
     * @return $this
     */
    public function setPDFExportArgs($args = [])
    {
        $this->exportPDFArgs[0] = array_get($args, 'paper', 'A4');
        $this->exportPDFArgs[1] = array_get($args, 'orientation', 'landscape');
        $this->exportPDFArgs[2] = array_get($args, 'zoomFactor', '1');

        return $this;
    }

    /**
     * Set maximum number of records for exporting
     * @param $exportRecordsLimit
     * @return $this
     */
    public function setExportRecordsLimit($exportRecordsLimit = -1)
    {
        $this->exportRecordsLimit = $exportRecordsLimit;

        return $this;
    }

    /**
     * Add filter
     * @param array $options filter options
     * @return $this
     */
    public function addFilter($options)
    {
        $defaultOptions = [
            'callback'    => null,
            'column'      => '',
            'core'        => true,
            'engine'      => 'QueryBuilder', // processing by eloquent
            'filterClass' => '',
            'labelClass'  => '',
            'type'        => '',
            // noclosure:
            // if false then callback is called inside closure that is passed to where()
            // it's default behaviour, but joins doesn't work with it
            'noClosure'   => false,
        ];

        // add timezone to daterange filter
        if ($options['type'] == 'daterange') {
            $options += [
                'timezone'         => $this->getTimezone(),
                'dbColumnTimezone' => array_get($options, 'dbColumnTimezone', 'UTC'),
            ];
        }

        $currentFilterOptions = array_merge($defaultOptions, $options);

        $this->filters[] = $currentFilterOptions;

        if ($currentFilterOptions['engine'] == 'Collection') {
            // set identifier that collection filters should be added
            $this->hasCollectionFilters = true;
        }

        return $this;
    }

    /**
     * Add filters
     * @param array $filtersOptions filters options
     * @param       self
     * @return $this
     */
    public function addFilters($filtersOptions)
    {
        foreach ($filtersOptions as $options) {
            $this->addFilter($options);
        }

        return $this;
    }

    /**
     * Returns array of all of datatable filters
     * @return array    datatable filters
     */
    public function filters()
    {
        // get filter options form URL and merge it with the default filter options
        $this->filters = (new DatatableFilter($this->currentRequest))->getFilterOptionsFromURL($this->filters);

        return $this->filters;
    }

    /**
     * Set query. Uses only for pre-filtering collection thru QueryBuilder
     * @param mixed $query Eloquent collection
     * @return mixed
     */
    public function prepareQuery($query)
    {
        $this->query = $query;

        // process filters to add it to the query
        (new DatatableFilter($this->currentRequest, $this->query))->process($this->filters);

        return $this->query;
    }

    /**
     * Get Datatables Html Builder instance.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function builder()
    {
        return app(AppBuilder::class)->applyRequest($this->currentRequest);
    }
}
