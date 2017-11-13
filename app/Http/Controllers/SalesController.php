<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\UserRepository as UserRepo;
use App\Models\Users as Users;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use SoapClient;
use SoapHeader;
use App\Models\Region;
use App\Models\AccessLogs;

class SalesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        //$this->userRepository = $userRepository;
    }

    public function getUserInfo()
    {
        return $this->withRole('Driver');
    }
    /**
     * Show the Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = Auth::user()->id;
        //$userData = Users::find($userId);
        $sales = Sales::where('user_id', '=', $userId)->get();

        return view('sales/sales', compact('sales'));
        //return View::make('profile',compact('userData'));
        //return view('profile');
    }

    public function create()
    {
        //$this->userRepository->getUsers();
        //Users::where()
        //Device::where('serial', $deviceSerial)->value('manufacturer');
        // Get the user data
        //$userroleId = user->id;echo "sdfsdfds".$userroleId;exit;
//        $userObj = new Users();
//        $userRoleId = Users::where('name','Supervisor')
//                            ->value('role_id');
//        $userRegionId = Users::where('name','Supervisor')
//                            ->value('region_id');
//        $userRole   = Users::where('role_id', '=', $userRoleId )->find(1)->Role;//echo "sdfsdfdsdsdd".$userRole;exit;
//        $userRegion = Users::where('region_id', '=', $userRegionId )->find(1)->Region;
        //$regionId = $userObj->Region()->toSql();echo "sdfdsf";var_dump($regionId);exit;
        //$roleId = $userObj->Role()->toSql();echo "sdfdsf";var_dump($roleId);exit;
        //$roleId = $userObj->userRole()->where('id', '=', $userRoleId)->toSql();echo "sdfdsf";var_dump($roleId);exit;
       // $roleId = $userObj->userRole();echo "sdfdsf";echo "<pre>";print_r($roleId);echo "</pre>";exit;
       //
       //
       //
        $userData = Users::get();
        $dateTime = Carbon::now()->toDateTimeString();
        $goldDetails = $this->getGoldPrice();
        $goldPrice =  $goldDetails->GetBaseMetalPriceResult->Price;

        return view('createsales', compact('dateTime', 'goldPrice'));
        //echo phpinfo();exit;


    }

    public function store(Request $request, Sales $sale)
    {
        $userId = Auth::user()->id;
        $sale->user_id       = $userId;
        $sale->sold_date     = $request->input('sold_date');
        $sale->customer_name = $request->input('name');
        $sale->ornament_name = $request->input('ornament');
        $sale->quantity_sold = $request->input('sold');
        $sale->gold_cost     = $request->input('cost');
        $sale->total_cost    = $request->input('total_cost');
        $sale->save();

        return redirect()->back()->with('message', 'Successfully updated user');

       // echo "Heloo";echo array_get($request, 'name');exit;
    }

    public function edit(Request $request, Sales $sale, $id)
    {
//        $userId     = Auth::user()->id;
//        $salesId    = Sales::where('id', '=', $id)->get();
        $saleData = Sales::where('id', '=', $id)->get();

//        if (!empty($user)) {
//            $sale->user_id       = $userId;
//            $sale->sold_date     = $request->input('sold_date');
//            $sale->customer_name = $request->input('name');
//            $sale->ornament_name = $request->input('ornament');
//            $sale->quantity_sold = $request->input('sold');
//            $sale->gold_cost     = $request->input('cost');
//            $sale->total_cost    = $request->input('total_cost');
//        $driver->update();
//        }

        return view('sales/edit', compact('saleData'));
//

//        return view('users_edit', compact('user', 'roles', 'regions'));
    }

    public function update(Request $request, Sales $editSale,$id)
    {
        $userId     = Auth::user()->id;
        $data =
        [
            'user_id' => $userId,
            'customer_name' =>  $request->input('name'),
            'sold_date' => $request->input('sold_date'),
            'ornament_name'=> $request->input('ornament'),
            'quantity_sold' => $request->input('sold'),
            'gold_cost' =>  $request->input('cost'),
            'total_cost' => $request->input('total_cost'),

        ];

        Sales::where('id', $id)->update($data);

        return redirect()->back()->with('message', 'Successfully updated Sales');
    }

    public function destroy($id)
    {

//        if (!$this->user->can(['web.full_access', 'web.users.destroy'])) {
//            return View::make('deployments.unauthorized');
//        }

        $destroySales = Sales::find($id);
        $destroySales->delete();
        return $this->index();
        // redirect
       // Session::flash('message', 'Successfully deleted the user!');
        //return Redirect::to('users');
    }

    public function getGoldPrice()
    {
    // define the SOAP client using the url for the service
    $client = new SoapClient('http://globalmetals.xignite.com/xGlobalMetals.asmx?WSDL');

    // create an array of parameters
    $param = array(
                   "MetalType" => "EngelhardGold",
                    "Currency" => "INR");

    // add authentication info
    $xignite_header = new SoapHeader('http://www.xignite.com/services/',
         "Header", array("Username" => "4FF07F18B7A84252ADFEA2E4802E19E8"));
    $client->__setSoapHeaders(array($xignite_header));

    // call the service, passing the parameters and the name of the operation
    $result['goldPrice'] = $client->GetBaseMetalPrice($param);
    // assess the results
    if (is_soap_fault($result)) {
         echo '<h2>Fault</h2><pre>';
         print_r($result);
         echo '</pre>';
    } else {
//         echo '<h2>Result</h2><pre>';
//         print_r($result);
//         echo '</pre>';
    }
    // print the SOAP request
//    echo '<h2>Request</h2><pre>' . htmlspecialchars($client->__getLastRequest(), ENT_QUOTES) . '</pre>';
//    // print the SOAP request Headers
//    echo '<h2>Request Headers</h2><pre>' . htmlspecialchars($client->__getLastRequestHeaders(), ENT_QUOTES) . '</pre>';
//    // print the SOAP response
//    echo '<h2>Response</h2><pre>' . htmlspecialchars($client->__getLastResponse(), ENT_QUOTES) . '</pre>';

    return $result['goldPrice'];
    }

    public function shopSales(Request $request, $id = null)
    {
        if ($id!='') {
            //$dat = $request->all(); print_r($dat);exit;
            //print_r(Input::all());
            $regionId = Region::where('id', '=', $id)->value('id');//echo "<pre>";print_r($regions['id']);echo "</pre>";exit;
            $users = Users::where('region_id', '=', $regionId)->get();//echo "<pre>";print_r($users);echo "</pre>";exit;
            $data = AccessLogs::whereIn('user_id', $users)->get();//echo "<pre>";print_r($data);echo "</pre>";exit;
            $regions['regions'] = Region::where('id', '=',$regionId)->pluck('name','id');//echo "<pre>";print_r($regions['regions']);echo "</pre>";exit;

            //return view('sales/shop-sales', compact('data', 'regions'));
            //return Response::eloquent($data);
            return response()->json(['return' => 'regions', 'data']);
        } else {
            $regions['regions'] = Region::pluck('name','id');
            $data = AccessLogs::with('users')->get();//echo "<pre>";print_r($data);echo "</pre>";exit;
//            $usersData = AccessLogs::with('users')->get();
//            echo "<pre>";print_r($usersData);echo "</pre>";exit;
            //$users = Users::where('id', '=', $region->user_id);
        }
//        $userId = Auth::user()->id;
//        //$userData = Users::find($userId);
//        $sales = Sales::where('user_id', '=', $userId)->get();
        return view('sales/shop-sales', compact('data', 'regions'));
    }
}
