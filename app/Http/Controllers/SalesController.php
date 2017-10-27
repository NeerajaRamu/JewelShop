<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\UserRepository as UserRepo;
use App\Models\Users as Users;
use Illuminate\Support\Facades\Auth;
use App\Models\Sales;
use Illuminate\Support\Facades\DB;

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
        $userData = Users::get();//echo "<pre>";print_r($userData);echo "</pre>";exit;

        return view('createsales', compact('userData'));
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

        return redirect()->back()->with('message', 'Successfully updated user');
    }

    public function destroy($id)
    {

//        if (!$this->user->can(['web.full_access', 'web.users.destroy'])) {
//            return View::make('deployments.unauthorized');
//        }

        $destroySales = Sales::find($id);
        $destroySales->delete();

        // redirect
       // Session::flash('message', 'Successfully deleted the user!');
        //return Redirect::to('users');
    }
}
