<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users as Users;
use Carbon;
use App\Models\AccessLogs;
use App\Models\Sales;

class ProfileController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function getUserInfo() {
        return $this->withRole('Driver');
    }

    /**
     * Show the Profile
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $userObj = new Users();
        $loginUser = \Auth::User()->name;
        $loginId = \Auth::User()->id;

        $userRoleId = Users::where('name', $loginUser)
                ->value('role_id');

        $userRegionId = Users::where('name', $loginUser)
                ->value('region_id');

        $userRole = Users::where('role_id', '=', $userRoleId)->find($loginId)->Role;
        $userRegion = Users::where('region_id', '=', $userRegionId)->find($loginId)->Region;
        $userData = Users::where('name', $loginUser)
                ->get();

        return view('profile', compact('userData', 'userRole', 'userRegion', 'loginUser'));
    }

    /**
     *
     * @param type $startTime
     * @param type $endTime
     * @return type
     */
    public function getTimeDiff($startTime, $endTime) {
        $nextDay = $startTime > $endTime ? 1 : 0;
        $dep = EXPLODE(':', $startTime);
        $arr = EXPLODE(':', $endTime);
        $diff = ABS(MKTIME($dep[0], $dep[1], 0, DATE('n'), DATE('j'), DATE('y')) - MKTIME($arr[0], $arr[1], 0, DATE('n'), DATE('j') + $nextDay, DATE('y')));
        $hours = FLOOR($diff / (60 * 60));
        $mins = FLOOR(($diff - ($hours * 60 * 60)) / (60));
        $secs = FLOOR(($diff - (($hours * 60 * 60) + ($mins * 60))));
        IF (STRLEN($hours) < 2) {
            $hours = "0" . $hours;
        }
        IF (STRLEN($mins) < 2) {
            $mins = "0" . $mins;
        }
        IF (STRLEN($secs) < 2) {
            $secs = "0" . $secs;
        }

        return $hours . ':' . $mins . ':' . $secs;
    }

    /**
     *
     * @return type
     */
    public function clockOut() {
        $userObj = new Users();
        $loginUser = \Auth::User()->name;
        $loginId = \Auth::User()->id;

        $userRoleId = Users::where('name', $loginUser)
                ->value('role_id');

        $userRegionId = Users::where('name', $loginUser)
                ->value('region_id');

        $userRole = Users::where('role_id', '=', $userRoleId)->find($loginId)->Role;
        $userRegion = Users::where('region_id', '=', $userRegionId)->find($loginId)->Region;
        $userData = Users::where('name', $loginUser)
                ->get();

        $mytime = Carbon\Carbon::now();
        $todaysdate = $mytime->toDateString();
        $timeIn = AccessLogs::where('user_id', '=', $loginId)
                ->where('date', '=', $todaysdate)
                ->value('time_in');

        $timeOut   = $mytime->toTimeString();
        $timeSpent = $this->getTimeDiff($timeIn, $timeOut);

        $totalAmount = Sales::where('user_id', '=', $loginId)
                            ->where('sold_date', '=', $todaysdate)
                            ->sum('total_cost');

        $totalGold = Sales::where('user_id', '=', $loginId)
                            ->where('sold_date', '=', $todaysdate)
                            ->sum('quantity_sold');

        return view('time-out', compact('timeIn', 'timeOut', 'timeSpent', 'totalAmount', 'totalGold', 'userRole', 'userRegion'));
    }

    /**
     *
     * @param Request $request
     * @param Users $user
     */
    public function logOutDetails(Request $request, Users $user) {
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = $request->input('role_id');
        $user->region_id = $request->input('region_id');
        $user->status = $request->input('status');
        $user->save();
    }

    /**
     *
     * @param Request $request
     * @return type
     * @throws \Exception
     */
    public function updateLogs(Request $request) {
        $loginId = \Auth::User()->id;
        $todaysDateTime = Carbon\Carbon::now();
        $todaysdate = $todaysDateTime->toDateString();
        $userLogData = AccessLogs::where('user_id', '=', $loginId)
                                ->where('date', '=', $todaysdate)
                                ->get();
        if (!empty($userLogData)) {
            $timeSpent = explode(':', $request->get('totalHours'));
            $data = [
                'time_out' => $request['timeOut'],
                'total_gold_sold' => $request['timeOut'],
                'total_amount' => $request['totalAmount'],
                'total_hours_spent' => $timeSpent[0],
                'total_gold_sold' => $request['totalGold'],
            ];

            try {
                AccessLogs::where('user_id', '=', $loginId)
                    ->where('date', '=', $todaysdate)
                    ->Update($data);
            } catch (\Exception $e) {
                throw new \Exception($e);
            }
        }

        return $this->clockOut();
    }

}
