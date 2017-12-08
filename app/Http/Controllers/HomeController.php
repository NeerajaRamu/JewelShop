<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AccessLogs;
use Carbon;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     *
     * @param AccessLogs $accessLogs
     * @return type
     */
    public function index(AccessLogs $accessLogs) {
        $todaysDateTime = Carbon\Carbon::now();
        $timeIn         = $todaysDateTime->toTimeString();
        $dateVal        = $todaysDateTime->toDateTimeString();
        $todaysdate     = $todaysDateTime->toDateString();

        $userData = AccessLogs::where('user_id', '=', Auth::user()->id)
                        ->where('date', '=', $todaysdate)
                        ->get();

        if (count($userData) == '0') {
            $accessLogs->user_id = Auth::user()->id;
            $accessLogs->date = $todaysdate;
            $accessLogs->time_in = $timeIn;
            $accessLogs->save();
        }

        return view('home');
    }
}
