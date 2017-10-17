<?php
namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Container as App;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class UserRepository extends BaseRepository
{

    protected $heartbeatRepository;

    public function __construct(
        App $app,
        Collection $collection
//        HeartbeatRepository $heartbeatRepository,
//        OptionsRepository $optionsRepository,
//        HosEventLog $hosEventLog
    ) {
        parent::__construct($app, $collection);

//        $this->heartbeatRepository = $heartbeatRepository;
//        $this->optionsRepository   = $optionsRepository;
//        $this->hosEventLog         = $hosEventLog;
    }

    public function model()
    {
        return 'App\Models\Users';
    }

    public function getUsers()
    {
        return $this->model->get();
    }

//    public function getDrivers()
//    {
//        return $this->withRole('Driver');
//    }
//
//    /**
//     * Get user data by id and cache results for quicker retrieval
//     *
//     * @param  $id
//     * @return mixed
//     */
//    public function getById($id, $forget = false)
//    {
//
//        // user cache key
//        $cacheKey     = $this->currentDeployment()->id . '.user.' . md5(__METHOD__ . $id);
//        $cacheMinutes = (24 * 60);
//
//        // forget/clear cached data for user
//        if ($forget) {
//            Cache::forget($cacheKey);
//        }
//
//        // get user data and cache until cleared
//        return Cache::remember($cacheKey, $cacheMinutes, function () use ($id) {
//            return $this->model->where('id', $id)->where('deployment_id', $this->currentDeployment()->id)->first();
//        });
//    }
//
//    /**
//     * Get user data by external id and cache results for quicker retrieval
//     *
//     * @param  $id
//     * @return mixed
//     */
//    public function getByExternalId($externalId, $forget = false)
//    {
//
//        // user cache key
//        $cacheKey     = $this->currentDeployment()->id . '.' . md5(__METHOD__ . $externalId);
//        $cacheMinutes = (24 * 60);
//
//        // forget/clear cached data for user
//        if ($forget) {
//            Cache::forget($cacheKey);
//        }
//
//        // get user data and cache until cleared
//        return Cache::remember($cacheKey, $cacheMinutes, function () use ($externalId) {
//            return $this->model->where('external_id', $externalId)
//                               ->where('deployment_id', $this->currentDeployment()->id)
//                               ->first();
//        });
//    }
//
//    /**
//     * Search user model for users with role name
//     *
//     * @param  $roleName
//     * @return mixed
//     */
//    public function withRole($roleName)
//    {
//        return $this->model->where('deployment_id', $this->currentDeployment()->id)
//                           ->whereHas('roles', function ($q) use ($roleName) {
//                               $q->where('name', $roleName);
//                           });
//    }
//
//    /**
//     * Get last heartbeat record for user
//     *
//     * @param $userId
//     *
//     * @return null
//     */
//    public function lastHeartbeat($userId)
//    {
//        return $this->heartbeatRepository->getLastHeartbeat($this->currentDeployment()->id, 'user', $userId);
//    }
//
//    /**
//     * Get current duty status for user
//     *
//     * @param $userId
//     * @return null
//     */
//    public function currentDutyStatus($userId)
//    {
//
//        // get cached latest event for user
//        $hosLogsRepository = app(HosLogsRepository::class);
//        $latestEvent       = $hosLogsRepository->latestEvent($userId);
//        $dutyStatus        = null;
//
//        if (!empty($latestEvent)) {
//            $dutyStatus = $latestEvent->duty_status;
//        } else {
//            // fall back to off duty if no events found
//            // (shouldn't happen unless db was cleared)
//            $dutyStatus = $this->optionsRepository->getOptionsByType('duty_status')
//                                                  ->where('machine_name', 'off_duty')
//                                                  ->first();
//        }
//
//        return $dutyStatus;
//    }
//
//    /**
//     * Get current user status for driver pages
//     *
//     * @param $userId
//     * @return \stdClass
//     */
//    public function currentUserStatus($userId)
//    {
//        $user              = $this->getById($userId);
//        $hosLogsRepository = app(HosLogsRepository::class);
//        $latestEvent       = $hosLogsRepository->latestEvent($userId);
//        $lastHeartBeat     = $this->lastHeartbeat($userId);
//        $coUser            = null;
//
//        if (!empty($latestEvent)) {
//            $dutyStatus = $latestEvent->duty_status;
//        } else {
//            // fall back to off duty if no events found
//            // (shouldn't happen unless db was cleared)
//            $dutyStatus = $this->optionsRepository->getOptionsByType('duty_status')
//                                                  ->where('machine_name', 'off_duty')
//                                                  ->first();
//        }
//
//        if (!empty($latestEvent->co_user_id)) {
//            $coUser = $this->getById($latestEvent->co_user_id);
//        }
//
//        $location = [];
//
//        if (!empty($lastHeartBeat)) {
//            $location = [
//                'lat_lng'    => round($lastHeartBeat->latitude, 4) . ', ' . round($lastHeartBeat->longitude, 4),
//                'location'   => $lastHeartBeat->location_description,
//                'created_at' => Carbon::parse($lastHeartBeat->created_at)->diffForHumans(),
//            ];
//        }
//
//        /**
//         * Get last device user was associated with
//         */
//        $tablet = !empty($user->user_device->device) ? $user->user_device->device : null;
//
//        /**
//         * Get tablet update data when user is logged in
//         */
//        $tabletHosUpdates = [];
//
//        if (!empty($tablet)) {
//            // get request logs for user's tablet
//            $setKey   = "requests.{$tablet->serial}";
//            $setCount = Redis::zcount($setKey, '-inf', '+inf');
//            $setData  = Redis::zrevrange($setKey, 0, $setCount, 'WITHSCORES');
//
//            foreach ($setData as $rKey => $rVal) {
//                $saveKey = false;
//
//                if (strpos($rKey, 'GET:api/hos') !== false && empty($tabletHosUpdates['get'])) {
//                    $saveKey = 'get';
//                }
//
//                if (strpos($rKey, 'POST:api/hos') !== false && empty($tabletHosUpdates['post'])) {
//                    $saveKey = 'post';
//                }
//
//                if ($saveKey) {
//                    $tabletHosUpdates[$saveKey] = [
//                        'time'  => (int) $rVal,
//                        'utc'   => date('Y-m-d H:i:s e', $rVal),
//                        'human' => Carbon::parse(date('r', $rVal))->diffForHumans(),
//                    ];
//                }
//            }
//        }
//
//        $currentUserStatus                      = new \stdClass();
//        $currentUserStatus->last_heart_beat     = $lastHeartBeat;
//        $currentUserStatus->duty_status         = $dutyStatus;
//        $currentUserStatus->co_user             = $coUser;
//        $currentUserStatus->last_known_location = $location;
//        $currentUserStatus->tablet              = $tablet;
//        $currentUserStatus->tablet_hos_updates  = $tabletHosUpdates;
//
//        return $currentUserStatus;
//    }
//
//
//    /**
//     * Search for a driver
//     *
//     * @param  String  $value        search string
//     * @param  Array   $matchColumns array of column names to match in query
//     * @param  Integer $limit        limits the count of results
//     * @return Collection of Users
//     */
//    public function search($value, $matchColumns, $limit = null, $extraParams = [])
//    {
//        $query = $this->withRole('Driver');
//        foreach ($matchColumns as $matchColumn) {
//            $query->orderBy($matchColumn, 'asc');
//        }
//        if (!empty($value)) {
//            $keywords = explode(' ', $value);
//            foreach ($keywords as $keyword) {
//                $keyword = "%{$keyword}%";
//                $query->where(
//                    function ($query) use ($keyword, $matchColumns) {
//                        foreach ($matchColumns as $matchColumn) {
//                            $query->orWhere($matchColumn, 'like', $keyword);
//                        }
//                    }
//                );
//            }
//        }
//
//        // exclude User IDs
//        if (!empty($extraParams['exclude_ids'])) {
//            $query->whereNotIn('id', $extraParams['exclude_ids']);
//        }
//
//        if ($limit !== null) {
//            $query->limit($limit);
//        }
//
//        return $query->get();
//    }
//
//    // TODO merge it with search() method
//    public function getByUsernameAndFullName($username = null, $fullname = null, $search = null)
//    {
//        $query = $this->model->where('deployment_id', $this->currentDeployment()->id);
//
//        if ($username) {
//            $query->where('username', $username);
//        }
//
//        if ($fullname) {
//            $pieces = explode(' ', $fullname, 2);
//            $query->where('first_name', trim(array_get($pieces, 0)));
//            $query->where('last_name', trim(array_get($pieces, 1)));
//        }
//
//        if ($search) {
//            $query->where(
//                function ($query) use ($search) {
//                    $query->where('username', 'LIKE', "%$search%")
//                          ->orWhere('first_name', 'LIKE', "%$search%")
//                          ->orWhere('last_name', 'LIKE', "%$search%");
//                }
//            );
//        }
//
//        return $query->get();
//    }
//
//    /**
//     * Get Non-Driver user
//     *
//     * @return mixed
//     */
//    public function getNonDriver()
//    {
//        return $this->withRole('Non-Driver')->firstOrFail();
//    }
//
//    /**
//     * returns users by ids
//     *
//     * @param  array   $id array of user's ID
//     * @param  boolean $isExternalId
//     * @return mixed
//     */
//    public function getUsersById($ids = [], $isExternalId = false)
//    {
//        if ($isExternalId) {
//            return $this->model->whereIn('external_id', $ids)->get();
//        } else {
//            return $this->model->whereIn('id', $ids)->get();
//        }
//    }
//
//    /**
//     * @return mixed
//     */
//    public function withRoleAndDeployment()
//    {
//        $query = $this->model
//            ->with('roles')
//            ->where('deployment_id', $this->currentDeployment()->id);
//
//        return $query;
//    }
//
//
//    /**
//     * Start new user employment period
//     * Refresh employment history cache after save
//     *
//     * @param      $userId
//     * @param null $startDate
//     */
//    public function startEmploymentPeriod($userId, $startDate = null)
//    {
//
//        $user = $this->getById($userId);
//        if (is_null($startDate)) {
//            $startDate = Carbon::now()->tz($user->terminal->time_zone);
//        }
//
//        // add user employment date start
//        $employment                = new Employment();
//        $employment->deployment_id = $user->deployment_id;
//        $employment->user_id       = $user->id;
//        $employment->start_date    = $startDate;
//        $employment->save();
//
//        Log::debug($userId . ' Employment Period Start ' . $startDate);
//
//        // cache employment history for user after saving
//        $this->employmentHistory($userId, true);
//    }
//
//    /**
//     * End user's current employment period
//     * Refresh employment history cache after save
//     *
//     * @param      $userId
//     * @param null $endDate
//     */
//    public function endEmploymentPeriod($userId, $endDate = null)
//    {
//
//        $user = $this->getById($userId);
//        if (is_null($endDate)) {
//            $endDate = Carbon::now()->tz($user->terminal->time_zone);
//        }
//
//        // update end date of user employment period
//        if (!empty($user->employment->first())) {
//            if ($employment = $user->employment->first()) {
//                $employment->end_date = $endDate;
//                $employment->save();
//                $employment->delete();
//            }
//        }
//
//        Log::debug($userId . ' Employment Period End ' . $endDate);
//
//        // cache employment history for user after saving
//        $this->employmentHistory($userId, true);
//    }
//
//    /**
//     * Get user employment history and cache results for quicker retrieval
//     *
//     * @param       $userId
//     * @param  bool $forget
//     * @return bool
//     */
//    public function employmentHistory($userId, $forget = false)
//    {
//
//        // user cache key
//        /*
//         * commenting out because of lada-cache now handles user caching
//         *
//        $cacheKey     = $this->currentDeployment()->id . '.user.employment.' . md5(__METHOD__ . $userId);
//        $cacheMinutes = (24 * 60);
//
//        // forget/clear cached data for user
//        if ($forget) {
//            Cache::forget($cacheKey);
//        }
//
//        // get user data and cache until cleared
//        return Cache::remember($cacheKey, $cacheMinutes, function () use ($userId) {
//            if ($user = $this->getById($userId)) {
//                Log::debug($userId . ' Employment History Cache Refreshed');
//                return $user->employment()->withTrashed()->get();
//            }
//            return false;
//        });
//
//        */
//
//
//        if ($user = $this->getById($userId)) {
//            return $user->employment()->withTrashed()->get();
//        }
//        return false;
//    }
//
//    /**
//     * get Current HOS Timer Data for given User
//     *
//     * @param  User $user User to get current HOS Timers for
//     * @return array timer data
//     */
//    public function getCurrentHOSTimerData(User $user)
//    {
//        $hosLogsRepository = app(HosLogsRepository::class);
//
//        // get single user's hos timer data
//        $timers    = $hosLogsRepository->getHosTimers($user->id);
//        $timerData = $timers['data'];
//
//        // replace $timerData['last_34hr_reset'] with compiled data (shows current cycle only)
//        $compiledLog = $hosLogsRepository->getHosByDate(
//            [$user->id], Carbon::now($user->terminal->time_zone)->toDateString()
//        );
//        if (!empty($compiledLog)) {
//            $compiledLog = $compiledLog[0];
//        }
//        $timerData['last_34hr_reset'] = !empty($compiledLog->last_34hr_reset_utc)
//            ? parseToFormat($compiledLog->last_34hr_reset_utc, $compiledLog->time_zone, 'Y-m-d H:i T')
//            : '';
//
//        $timerData['passenger_rule_violation'] = $hosLogsRepository->getCurrentPassengerSeatViolation($user->id);
//
//        return $timerData;
//    }

}
