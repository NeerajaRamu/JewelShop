<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Repositories\UserRepository as UserRepo;
use App\Models\Users as Users;
//use App\Http\Controllers\Auth as Auth;
use Carbon;
use App\Models\AccessLogs;
use App\Models\Sales;

class DefaultProfileController extends Controller
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
        $loginId = \Auth::User()->id;

        $userRoleId = Users::where('name',$loginUser)
                            ->value('role_id');

        $userRegionId = Users::where('name',$loginUser)
                            ->value('region_id');

        $userRole   = Users::where('role_id', '=', $userRoleId )->find($loginId)->Role;//echo "Rold id".$userRoleId;exit;
        $userRegion = Users::where('region_id', '=', $userRegionId )->find($loginId)->Region;
        $userData = Users::where('name',$loginUser)
                            ->get();

        return view('profile', compact('userData', 'userRole', 'userRegion'));
    }


    public function getTimeDiff($dtime,$atime)
            {


 $nextDay=$dtime>$atime?1:0;
 $dep=EXPLODE(':',$dtime);
 $arr=EXPLODE(':',$atime);
 $diff=ABS(MKTIME($dep[0],$dep[1],0,DATE('n'),DATE('j'),DATE('y'))-MKTIME($arr[0],$arr[1],0,DATE('n'),DATE('j')+$nextDay,DATE('y')));
 $hours=FLOOR($diff/(60*60));
 $mins=FLOOR(($diff-($hours*60*60))/(60));
 $secs=FLOOR(($diff-(($hours*60*60)+($mins*60))));
 IF(STRLEN($hours)<2){$hours="0".$hours;}
 IF(STRLEN($mins)<2){$mins="0".$mins;}
 IF(STRLEN($secs)<2){$secs="0".$secs;}
 RETURN $hours.':'.$mins.':'.$secs;

//  $start = strtotime($dtime);
//$end   = strtotime($atime);
//$diff  = $end - $start;
//
//$hours = floor($diff / (60 * 60));
//$minutes = $diff - $hours * (60 * 60);
//echo 'Remaining time: ' . $hours .  ' hours, ' . floor( $minutes / 60 ) . ' minutes';exit;
}
    public function clockOut()
    {
        $userObj = new Users();
        $loginUser = \Auth::User()->name;
        $loginId = \Auth::User()->id;

        $userRoleId = Users::where('name',$loginUser)
                            ->value('role_id');

        $userRegionId = Users::where('name',$loginUser)
                            ->value('region_id');

        $userRole   = Users::where('role_id', '=', $userRoleId )->find(1)->Role;
        $userRegion = Users::where('region_id', '=', $userRegionId )->find(1)->Region;
        $userData   = Users::where('name',$loginUser)
                            ->get();

        $timeIn      = AccessLogs::where('user_id', '=', $loginId)->value('time_in');
        $mytime      = Carbon\Carbon::now();
        $timeOut     = $mytime->toTimeString();
        $timeSpent   = $this->getTimeDiff($timeIn,$timeOut);
        $totalAmount = Sales::where('user_id', '=', $loginId)->sum('total_cost');
        $totalGold   = Sales::where('user_id', '=', $loginId)->sum('quantity_sold');

//        $time1 = '09:19:00';
//$time2= '11:01:00';
//
//echo "t1".$t1=strtotime($timeIn);
//echo "<br/>t2:".$t2=strtotime($timeOut);
//
//echo "<br/>end:".$end=strtotime('14:30:00');
//echo  "<br/>floor value:";
//var_dump(floor((($end- $t1)/60)/60));
//
////$Hours =floor((($t2 - $t1)/60)/60);
//
//$Hours = floor((($t2- $t1)/60)/60);
//
//echo   $Hours.' Hours ';exit;

//$datetime1 = new Time('2014-02-11 04:04:26 AM');
//$datetime2 = new Time('2014-02-11 05:36:56 AM');
//$interval = $datetime1->diff($datetime2);
//echo $interval->format('%h')." Hours ".$interval->format('%i')." Minutes";
//exit;


        return view('time-out', compact('timeIn', 'timeOut', 'timeSpent', 'totalAmount', 'totalGold', 'userRole', 'userRegion'));
    }



    public function logOutDetails(Request $request, Users $user)
    {
        $user->name         = $request->input('name');
        $user->email        = $request->input('email');
        $user->password     = Hash::make($request->input('password'));
        $user->role_id      = $request->input('role_id');
        $user->region_id    = $request->input('region_id');
        $user->status       = $request->input('status');
        $user->save();
    }

    public function updateLogs(Request $request)
    {
        $loginId = \Auth::User()->id;
        $todaysDateTime = Carbon\Carbon::now();
        $todaysdate = $todaysDateTime->toDateString();
        //$timeIn     = AccessLogs::where('user_id', '=', $loginId)->value('time_in');
        $userLogData  = AccessLogs::where('user_id', '=', $loginId)
                                    ->where('date', '=', $todaysdate)
                                    ->get();
        if(!empty($userLogData))
        { $timeSpent =  explode(':',$request->get('totalHours'));
           $data = [
            'time_out'          => $request['timeOut'],
            'total_gold_sold'   => $request['timeOut'],
            'total_amount'      => $request['totalAmount'],
            'total_hours_spent' => $timeSpent[0],
            'total_gold_sold' => $request['totalGold'],
        ];

        try {
            echo AccessLogs::where('user_id', '=', $loginId)->Update($data);
//echo AccessLogs::createOrUpdate($data)->toSql();exit;
        } catch (\Exception $e) { throw new \Exception($e);
            echo $e;
            //throw new BadRequestHttpException($e);
        }
        }
        return $this->clockOut();
    }
}
