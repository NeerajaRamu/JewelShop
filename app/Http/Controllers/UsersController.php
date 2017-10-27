<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\UserRepository as UserRepo;
use App\Models\Users as Users;
use App\Models\Roles as Roles;
use App\Models\Region as Regions;
use Illuminate\Support\Facades\Hash;

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
        //$this->userRepository->getUsers();
        //Users::where()
        //Device::where('serial', $deviceSerial)->value('manufacturer');
        // Get the user data
        //$userroleId = user->id;echo "sdfsdfds".$userroleId;exit;
//        $userObj = new Users();


//        $query = Users::with('roles')->where('role_id', '1');
//echo "sdfdsfsf";echo "<pre>";print_r($query);echo "</pre>";exit;
//$query = $this->model
//            ->with('roles')
//            ->where('deployment_id', $this->currentDeployment()->id);


//        $userRoleId = Users::where('name','Supervisor')
//                            ->value('role_id');
//        $userRegionId = Users::where('name','Supervisor')
//                            ->value('region_id');
//        $userData['userRole']   = Users::find(1)->Role->name;
//        $userData['userRegion'] = Users::find(1)->Region->name;//echo "Sdfsdfds";echo "<pre>";print_r($userData);echo "</pre>";exit;
        //echo "Sdfsdfds";echo "<pre>";print_r($userData['userRegion']['name']);echo "</pre>";exit;
        //$userData['userRegion'] = Users::where('region_id', '=', $userRegionId )->find(1)->Region;
        //$regionId = $userObj->Region()->toSql();echo "sdfdsf";var_dump($regionId);exit;
        //$roleId = $userObj->Role()->toSql();echo "sdfdsf";var_dump($roleId);exit;
        //$roleId = $userObj->userRole()->where('id', '=', $userRoleId)->toSql();echo "sdfdsf";var_dump($roleId);exit;
       // $roleId = $userObj->userRole();echo "sdfdsf";echo "<pre>";print_r($roleId);echo "</pre>";exit;
//        $users = Users::with('role')->get();
//        echo "sdfdsf";echo "<pre>";print_r($users);echo "</pre>";exit;
//$students = Users::whereHas(
//    'roles', function($q){
//        $q->where('name', 'Supervisor');
//    }
//)->get();echo "<pre>";print_r($students);echo "</pre>";exit;
//echo "<pre>";print_r($users->with('Role')->first());echo "</pre>";exit;
        //$userData = $users->with('Role')->all();
     //   $userData = $users::with('Role');
      //  $userData['userRole']   = $users->with('Role');
       // $userData['userRegion'] = Users::find(1)->Region->name;
//echo $userData->role->name ;exit;



        $userData = Users::get();

        $userData = Users::with('role')->get();

        $userData = Users::with('region')->get();

//        foreach ($users1 as $usr) {
//            $userData['roles'] =  $usr->role->name;
//            echo '<br>';
//        }


       // echo "<pre>";print_r($userData);echo "</pre>";exit;

        return view('users', compact('userData'));
        //return View::make('profile',compact('userData'));
        //return view('profile');
    }

    public function create()
    {
        $roles      = Roles::all();
        $regions    = Regions::all();
        $status     = array ('1' => 'Active', '0' => 'Inactive');

        return view('create', compact('roles', 'regions', 'status'));
    }

    public function store(Request $request, Users $user)
    {
        $user->name         = $request->input('name');
        $user->email        = $request->input('email');
        $user->password     = Hash::make($request->input('password'));
        $user->role_id      = $request->input('role_id');
        $user->region_id    = $request->input('region_id');
        $user->status       = $request->input('status');
        $user->save();

        return redirect()->back()->with('message', 'Successfully updated user');

       // echo "Heloo";echo array_get($request, 'name');exit;
    }

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

    public function update(Request $request, $id)
    {
        $editUser = Users::find($id);

        $editUser->name         = $request->input('name');
        $editUser->email        = $request->input('email');
        $editUser->password     = Hash::make($request->input('password'));
        $editUser->role_id      = $request->input('role_id');
        $editUser->region_id    = $request->input('region_id');

        $editUser->save();

        return redirect()->back()->with('message', 'Successfully updated user');

    }

    public function destroy($id)
    {

//        if (!$this->user->can(['web.full_access', 'web.users.destroy'])) {
//            return View::make('deployments.unauthorized');
//        }

        $destroyUser = Users::find($id);
        $destroyUser->delete();

        // redirect
       // Session::flash('message', 'Successfully deleted the user!');
        return Redirect::to('users');
    }
}
