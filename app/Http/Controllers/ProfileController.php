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
        $userObj = new Users();
        $loginUser = \Auth::User()->name;

        $userRoleId = Users::where('name',$loginUser)
                            ->value('role_id');

        $userRegionId = Users::where('name',$loginUser)
                            ->value('region_id');

        $userRole   = Users::where('role_id', '=', $userRoleId )->find(1)->Role;
        $userRegion = Users::where('region_id', '=', $userRegionId )->find(1)->Region;
        $userData = Users::where('name',$loginUser)
                            ->get();

        return view('profile', compact('userData', 'userRole', 'userRegion'));
    }
}
