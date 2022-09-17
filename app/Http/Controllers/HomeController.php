<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        $users = Storage::disk('local')->get('users.json');
        $users = json_decode($users, true);

        $user_logs = Storage::disk('local')->get('logs.json');
        $user_logs = json_decode($user_logs, true);

        $users_collection = collect($users);
        $user_logs_collection = collect($user_logs)->groupBy('user_id');

        $users = $users_collection->reduce(function ($dashboard_data, $user, $key) use ($user_logs_collection) {
            $user_id = $user['id'];
            $user_log = $user_logs_collection->get($user_id);

            $data = $user_log->countBy(function ($log){
                return $log['type'];
            });

            $conversionsGraphData = $user_log->groupBy(function($log) {
                return (new \DateTime($log['time']))->format('Y-m-d');
            });

            $conversionsCount = $conversionsGraphData->mapWithKeys(function ($item, $key) {
                $logs = collect($item);
                $logs_count = $logs->countBy(function ($log) {
                    return $log['type'];
                });

                if (isset($logs_count['conversion'])) {
                    return [$key => $logs_count['conversion']];
                } else {
                    return [$key => 0];
                }
            });

            $revenue = $user_log->sum('revenue');

            $dashboard_data[$key] = $user;
            $dashboard_data[$key]['impression'] = $data['impression'];
            $dashboard_data[$key]['conversion'] = $data['conversion'];
            $dashboard_data[$key]['revenue'] = $revenue;
            $dashboard_data[$key]['graph_data'] = $conversionsCount;

            return $dashboard_data;
        },[]);

        $sortBy = $request->get('sort_by','');
        if ($sortBy != '') {
            $users = collect($users)->sortBy($sortBy)->paginate(15);
        } else {
            $users = collect($users)->paginate(15);
        }

        return view('index', compact('users'));
    }
}
