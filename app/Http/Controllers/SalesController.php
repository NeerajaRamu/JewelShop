<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users as Users;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SoapClient;
use SoapHeader;
use App\Models\Region;
use App\Models\AccessLogs;
use App\DataTables\BaseDataTable;
use Yajra\DataTables\Services\DataTable;

class SalesController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
        //$this->userRepository = $userRepository;
    }

    /**
     * Show the Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $userId = Auth::user()->id;
        //$sales = Sales::where('user_id', '=', $userId)->get();
        //echo "Query is".$sales;exit;
        $sales = Sales::where('user_id', '=', $userId)->Paginate(2);

        return view('sales/sales', compact('sales'));
    }

    /**
     *
     * @return type
     */
    public function create() {
        $userData = Users::get();
        $todaysDate = Carbon::now()->toDateString();
        $goldDetails = $this->getGoldPrice();
        $goldPrice = $goldDetails->GetBaseMetalPriceResult->Price;

        return view('createsales', compact('todaysDate', 'goldPrice'));
    }

    /**
     *
     * @param Request $request
     * @param Sales $sale
     * @return type
     */
    public function store(Request $request, Sales $sale) {
        $userId              = Auth::user()->id;
        $sale->user_id       = $userId;
        $sale->sold_date     = $request->input('sold_date');
        $sale->customer_name = $request->input('name');
        $sale->ornament_name = $request->input('ornament');
        $sale->quantity_sold = $request->input('sold');
        $sale->gold_cost     = $request->input('cost');
        $sale->total_cost    = $request->input('total_cost');
        $sale->save();

        return redirect()->back()->with('message', 'Created Sales successfully');
    }

    /**
     *
     * @param Request $request
     * @param Sales $sale
     * @param type $id
     * @return type
     */
    public function edit(Request $request, Sales $sale, $id) {
        $saleData = Sales::where('id', '=', $id)->get();

        return view('sales/edit', compact('saleData'));
    }

    /**
     *
     * @param Request $request
     * @param Sales $editSale
     * @param type $id
     * @return type
     */
    public function update(Request $request, Sales $editSale, $id) {

        $userId = Auth::user()->id;
        $data = [
            'user_id' => $userId,
            'customer_name' => $request->input('name'),
            'sold_date' => $request->input('sold_date'),
            'ornament_name' => $request->input('ornament'),
            'quantity_sold' => $request->input('sold'),
            'gold_cost' => $request->input('cost'),
            'total_cost' => $request->input('total_cost'),
        ];

        Sales::where('id', $id)->update($data);

        return redirect()->back()->with('message', 'Successfully updated Sales');
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function destroy($id) {
        $destroySales = Sales::find($id);
        $destroySales->delete();

        return $this->index();
    }

    /**
     *
     * @return type
     */
    private function getGoldPrice() {
        // define the SOAP client using the url for the service
        $client = new SoapClient('http://globalmetals.xignite.com/xGlobalMetals.asmx?WSDL');

        // create an array of parameters
        $param = array(
            "MetalType" => "EngelhardGold",
            "Currency" => "INR");

        // add authentication info
        $xignite_header = new SoapHeader('http://www.xignite.com/services/', "Header", array("Username" => "4FF07F18B7A84252ADFEA2E4802E19E8"));
        $client->__setSoapHeaders(array($xignite_header));

        // call the service, passing the parameters and the name of the operation
        $result['goldPrice'] = $client->GetBaseMetalPrice($param);
        // assess the results
        if (is_soap_fault($result)) {
            echo '<h2>Fault</h2><pre>';
            print_r($result);
            echo '</pre>';
        }

        return $result['goldPrice'];
    }

    /**
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function shopSales(Request $request, $id = null) {
        if ($id != '') {
            $regionId = Region::where('id', '=', $id)->value('id');
            $users = Users::where('region_id', '=', $regionId)->get();
            $data = AccessLogs::whereIn('user_id', $users)->get();
            $regions['regions'] = Region::where('id', '=', $regionId)->pluck('name', 'id');

            return response()->json(['return' => 'regions', 'data']);
        } else {
            $regions['regions'] = Region::pluck('name', 'id');
            $data = AccessLogs::with('users')->get();
        }
        $data = AccessLogs::paginate(3);

        return view('sales/shop-sales', compact('data', 'regions'));
    }

    /**
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function RegionShopSales(Request $request, $id = null) {
        $regionsDetails = Region::pluck('name', 'id');
        $data = [];

        foreach ($regionsDetails as $key => $value) {
            $sales['key'][] = $value;
            $sales[$value] = DB::select("SELECT u.region_id, SUM(a.total_gold_sold) as tot  FROM Jewel_test.users as u join access_logs as a where u.region_id = '" . $key . "' and a.user_id=u.id
GROUP BY u.region_id");
            if (!empty($sales[$value])) {
                $sal = ((array) $sales[$value][0]);
                $data[$value] = $sal['tot'];
            }
        }

        foreach ($data as $key => $value) {
            $names[] = $key;
            $values[] = $value;
        }
        $values[] = array_push($values, "0");
        $chartjs = app()->chartjs
                ->name('lineChartTest')
                ->type('line')
                ->size(['width' => 400, 'height' => 200])
                ->labels($names)
                ->datasets([
                    [
                        "label" => "Monthly Gold Sales",
                        'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                        'borderColor' => "rgba(38, 185, 154, 0.7)",
                        "pointBorderColor" => "rgba(38, 185, 154, 0.7)",
                        "pointBackgroundColor" => "rgba(38, 185, 154, 0.7)",
                        "pointHoverBackgroundColor" => "#fff",
                        "pointHoverBorderColor" => "rgba(220,220,220,1)",
                        'data' => $values,
                    ],
                ])
                ->options([]);

        return view('sales/region-shop-sales', compact('chartjs'));
    }

    /**
     *
     * @return type
     */
    public function chartjs() {
        $regionsDetails = Region::pluck('name', 'id');
        $data = [];

        foreach ($regionsDetails as $key => $value) {
           // $regions[] = $value;
            $sales['key'][] = $value;
            $sales[$value]  = DB::select("SELECT u.region_id, SUM(a.total_gold_sold) as tot  FROM Jewel_test.users as u join access_logs as a where u.region_id = '" . $key . "' and a.user_id=u.id
GROUP BY u.region_id");

            if (!empty($sales[$value])) {
                $sal          = ((array) $sales[$value][0]);
                $data[$value] = $sal['tot'];
            }
            else
            {
                $data[$value] = $value;
            }
        }
//print_r($data);exit;
        foreach ($data as $key => $value) {
            $viewer[] = $key;
            $click[]  = $value;
        }
        $click[] = array_push($click, "0");

        return view('chartjs')
                ->with('viewer', json_encode($viewer, JSON_NUMERIC_CHECK))
                ->with('click', json_encode($click, JSON_NUMERIC_CHECK));
    }

//    public function searchSales(Request $request, $id = null)
//    {
//        if ($id != '') {
//                $regionId = Region::where('id', '=', $id)->value('id');
//                $users = Users::where('region_id', '=', $regionId)->get();
//                $data = AccessLogs::whereIn('user_id', $users)->get();
//                $regions['regions'] = Region::pluck('name', 'id');
//                $selRegion = Region::where('id', '=', $regionId)->pluck('name', 'id');
//
//                //return view("shop-sales")->with("regions", $data);
//                //return Redirect::route('searchsales')->with('data', $regions);
//                //return response()->json(['return' => 'regions', 'data']);
//                return view('sales/shop-sales')->with('data', 'regions','selRegion',  json_decode($data, true), $regions, $selRegion);
//            }
//            else
//            {
//                echo "nooo data";
//            }
//    }

    public function searchSales(Request $request, $id = null)
    {
    $dataTable = new BaseDataTable(
            $this->processAjaxData(),
            $this->processHtmlBuilderData()
        );
echo "Hiiiiiiiii1223344";exit;
        $dataTable
            ->useTimezone($this->getTimezone())
            ->setExportFilename('DriverParameterGroup')
            ->addFilter([
                'callback' => function ($query, $keyword) {
                    if (!empty($keyword)) {
                        $keywords = explode(' ', $keyword);
                        foreach ($keywords as $kw) {
                            if (!empty($kw)) {
//                                $kw = "%{$kw}%";
//                                $query->where(function ($query) use ($kw) {
//                                    $query->where('driverParameterGroups.name', 'like', $kw)
//                                          ->orWhere('driverParameterGroups.machine_name', 'like', $kw);
//                                });
                                echo "Hiiiii";exit;
                            }
                        }
                    }
                },
                'column'   => 'driverParameterGroup',
                'label'    => 'DriverParameterGroup',
                'type'     => 'search',
            ]);

        $datatableFilters = $dataTable->filters();
        $records          = $this->driverParameterGroupRepository->getParameterGroupsByDeployment($this->currentDeployment->id);

        return $dataTable->render('drivers.driver_parameter_groups.index', $records, compact('datatableFilters'));
        //return view('drivers.driver_parameter_groups.index');
    }

    /**
     * Process ajax data
     * @return \Closure
     */
    private function processAjaxData()
    {
        return function ($ajaxData) {
            // fixed ordering for computed columns
            $ajaxData->orderColumn('user_id', 'user_id $1');
                   //  ->setTransformer(DriverParameterGroupTransformer::class);
           // return $ajaxData;
        };
    }

    /**
     * Process html builder data
     * @return \Closure
     */
    private function processHtmlBuilderData()
    {
        /**
         * @param \Yajra\Datatables\Html\Builder $builder
         *
         * @return mixed
         */
        return function ($builder) {
            $builder->columns([
                'name'                      => [
                    'title' => 'Name',
                ],
                'machine_name'              => [
                    'title' => 'Machine Name',
                ],
                'stop_idle_threshold'       => [
                    'title' => 'Stop Idle Threshold',
                ],
                'over_speed_threshold'      => [
                    'title' => 'Over Speed Threshold',
                ],
                'excessive_speed_threshold' => [
                    'title' => 'Excessive Over Speed',
                ],
                'over_rpm_threshold'        => [
                    'title' => 'Over RPM Threshold',
                ],
                'excessive_rpm_threshold'   => [
                    'title' => 'Excessive RPM Threshold',
                ],
                'pto_enabled'               => [
                    'title' => 'PTO Enabled',
                ],
                'pto_startup_time'          => [
                    'title' => 'PTO Start Up Time',
                ],
                'actions'                   => [
                    'title'      => 'Actions',
                    'className'  => 'text-center',
                    'orderable'  => false,
                    'printable'  => false,
                    'exportable' => false,
                ],
            ])->parameters([
                'dom'     => '<"html5buttons"B>lgiprtip',
                'order'   => [[1, 'asc']],
                'buttons' => [
                    'csv',
                    'excel',
                    'pdf',
                    'print',
                    'reload',
                ],
            ])->table([
                'class' => 'datatable table-bordered table-striped',
                'width' => '100%',
            ]);

            return $builder;
        };
    }

    public function allPosts(Request $request)
    {

        $columns = array(
                            0 =>'id',
                            1 =>'time_in',
                            2=> 'time_out',
                            3=> 'total_gold_sold',
                            4=> 'total_amount',
                        );

        $totalData = \Users::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value')))
        {
            $posts = \Users::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value');

            $posts = \Users::where('id','LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = \Users::where('id','LIKE',"%{$search}%")
                             ->count();
        }

        $data = array();
        if(!empty($posts))
        {
            foreach ($posts as $post)
            {
//                $show =  route('posts.show',$post->id);
//                $edit =  route('posts.edit',$post->id);

                $nestedData['id'] = $post->id;
                $nestedData['time_in'] = $post->time_in;
                $nestedData['time_out'] = $post->time_out;
                $nestedData['total_gold_sold'] = $post->total_gold_sold;
                //$nestedData['options'] = "&emsp;<a href='{$show}' title='SHOW' ><span class='glyphicon glyphicon-list'></span></a>
                //                          &emsp;<a href='{$edit}' title='EDIT' ><span class='glyphicon glyphicon-edit'></span></a>";
                $data[] = $nestedData;

            }
        }

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($totalData),
                    "recordsFiltered" => intval($totalFiltered),
                    "data"            => $data
                    );

        echo json_encode($json_data);

    }

    public function allSales()
    {
        $regions['regions'] = Region::pluck('name', 'id');
        $data = AccessLogs::with('users')->get();
        return view ( 'all-sales' )->withData($data );
    }

    public function getBasic()
    {
        return view('all-sales');
    }

    public function getBasicData()
    {
        $regions['regions'] = Region::pluck('name', 'id');
        $users = AccessLogs::with('users')->get();

        return Datatables::of($users)->make();
    }
}
