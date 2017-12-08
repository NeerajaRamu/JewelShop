<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users as Users;
use App\Models\Roles as Roles;
use App\Models\Region as Regions;
use Illuminate\Support\Facades\Hash;
use App\Models\AccessLogs;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Users $users)
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
    public function index(Users $users)
    {

        $userData = Users::get();
        $userData = Users::with('role')->get();
        $userData = Users::with('region')->get();
        $userData = Users:: paginate(2);

        return view('users', compact('userData'));
    }

    /**
     *
     * @return type
     */
    public function create()
    {
        $roles      = Roles::all();
        $regions    = Regions::all();
        $status     = array ('1' => 'Active', '0' => 'Inactive');

        return view('create', compact('roles', 'regions', 'status'));
    }

    /**
     *
     * @param Request $request
     * @param Users $user
     * @return type
     */
    public function store(Request $request, Users $user)
    {
        $user->name         = $request->input('name');
        $user->email        = $request->input('email');
        $user->password     = Hash::make($request->input('password'));
        $user->role_id      = $request->input('role_id');
        $user->region_id    = $request->input('region_id');
        $user->status       = $request->input('status');
        $user->save();

        return redirect()->back()->with('message', 'Successfully Created User');
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function edit($id)
    {
        $user = Users::where('id', '=', $id)->get();
        $roleId = $user[0]['role_id'];
        $regionId = $user[0]['region_id'];

        $user[0]['role'] = Roles::where('id', '=', $roleId)->value('name');
        $user[0]['region'] = Regions::where('id', '=', $regionId)->value('name');
        $roles = Roles::all();
        $regions = Regions::all();

        return view('users_edit', compact('user', 'roles', 'regions'));
    }
    /**
     *
     * @param Request $request
     * @param type $id
     * @return type
     */
    public function update(Request $request, $id)
    {
        $editUser = Users::find($id);

        $editUser->name         = $request->input('name');
        $editUser->email        = $request->input('email');
        $editUser->password     = Hash::make($request->input('password'));
        $editUser->role_id      = $request->input('role_id');
        $editUser->region_id    = $request->input('region_id');
        $editUser->save();

        return redirect()->back()->with('message', 'Successfully Updated User');
    }

    /**
     *
     * @param type $id
     * @return type
     */
    public function destroy($id)
    {
        $destroyUser = Users::find($id);
        $destroyUser->delete();

        return redirect()->back()->with('message', 'Successfully Deleted User');
    }

    /**
     *
     * @param Users $users
     * @return type
     */
    public function getTimesheet(Users $users)
    {
        $loginId = \Auth::User()->id;

        $timesheetData = AccessLogs::where('user_id', '=', $loginId)->get();

        return view('timesheet', compact('timesheetData'));
    }
}
