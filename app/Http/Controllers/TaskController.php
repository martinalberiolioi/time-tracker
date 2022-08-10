<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use DateTime;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $total_elapsed_time = 0;
        $tasks = Task::all();
        $results = [];
        
        foreach($tasks as $t) {
            $total_task_time = 0;

            if(!in_array($t->name, array_column($results, 'name'))) {
                $ocurrences = $tasks->where('name', $t->name);

                foreach($ocurrences as $o) {
                    $total_task_time += $o->elapsed_seconds;
                }

                // Converting from seconds to time
                $t_parsed = date('H:i:s', $total_task_time);
                $results[] = ['name' => $t->name, 'total_task_time' => $t_parsed];
                
                $total_elapsed_time += $total_task_time;
            }
        }

        $tt_parsed = date('H:i:s', $total_elapsed_time);

        return view('home', ['results' => $results, 'total_time' => $tt_parsed]);
    }

    public function store(Request $request)
    {
        $name = $request->task_name;

        $st_seconds = $request->start_time / 1000;
        $st_parsed = date('Y-m-d H:i:s', $st_seconds);

        // If it doesn't have an end time, it's because it hasn't stopped running yet
        if(empty($request->end_time)) {
            $task = new Task();
            $task->status = 'Running';
        } else {
            $task = Task::where(['name' => $name, 'start_time' => $st_parsed, 'end_time' => null])->first();

            $et_seconds = $request->end_time / 1000;
            $et_parsed = date('Y-m-d H:i:s', $et_seconds);

            $task->end_time = $et_parsed;
            $task->elapsed_seconds = $et_seconds - $st_seconds;
            $task->status = 'Stopped';
        }

        $task->name = $name;
        $task->start_time = $st_parsed;

        $task->save();
    }
}
