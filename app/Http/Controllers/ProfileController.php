<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\UserRepository as UserRepo;
use App\Models\Users as Users;
//use App\Http\Controllers\Auth as Auth;

class ProfileController extends Controller
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
        //$this->userRepository->getUsers();
        //Users::where()
        //Device::where('serial', $deviceSerial)->value('manufacturer');
        // Get the user data
        //$userroleId = user->id;echo "sdfsdfds".$userroleId;exit;
        $userObj = new Users();
        $loginUser = \Auth::User()->name;

        $userRoleId = Users::where('name',$loginUser)
                            ->value('role_id');

        $userRegionId = Users::where('name',$loginUser)
                            ->value('region_id');

        $userRole   = Users::where('role_id', '=', $userRoleId )->find(1)->Role;
        $userRegion = Users::where('region_id', '=', $userRegionId )->find(1)->Region;
        //$regionId = $userObj->Region()->toSql();echo "sdfdsf";var_dump($regionId);exit;
        //$roleId = $userObj->Role()->toSql();echo "sdfdsf";var_dump($roleId);exit;
        //$roleId = $userObj->userRole()->where('id', '=', $userRoleId)->toSql();echo "sdfdsf";var_dump($roleId);exit;
       // $roleId = $userObj->userRole();echo "sdfdsf";echo "<pre>";print_r($roleId);echo "</pre>";exit;
        $userData = Users::where('name','Supervisor')
                            ->get();//echo "<pre>";print_r($userData);echo "</pre>";exit;

        return view('profile', compact('userData', 'userRole', 'userRegion'));
        //return View::make('profile',compact('userData'));
        //return view('profile');
    }
}
