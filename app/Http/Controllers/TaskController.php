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
                $results[] = [
                        'name' => $t->name, 
                        'start_time' => $t->start_time, 
                        'end_time' => $t->end_time, 
                        'total_task_time' => $t_parsed,
                        'status' => $t->status];
                
                $total_elapsed_time += $total_task_time;
            }
        }

        $tt_parsed = date('H:i:s', $total_elapsed_time);

        if(isset($request->origin) && $request->origin == 'CMD') {
            return ['results' => $results, 'total_time' => $tt_parsed];
        }

        return view('home', ['results' => $results, 'total_time' => $tt_parsed]);
    }

    public function store(Request $request)
    {
        $name = $request->task_name;

        $st_seconds = $request->start_time / 1000;
        $st_parsed = date('Y-m-d H:i:s', $st_seconds);

        // Searchs for the running task
        $task = Task::where(['name' => $name, 'end_time' => null, 'status' => 'Running'])->first();

        if($request->action == 'start') {
            if(!isset($task)) {
                $task = new Task();
                $task->start_time = $st_parsed;
                $task->status = 'Running';
            } else {
                throw new \Exception('The inserted task is already running');
            }
        } else {
            if(isset($task)) {
                $et_seconds = $request->end_time / 1000;
                $et_parsed = date('Y-m-d H:i:s', $et_seconds);
    
                $task->end_time = $et_parsed;
                $task->elapsed_seconds = $et_seconds - $st_seconds;
                $task->status = 'Stopped';
            } else {
                throw new \Exception('The inserted task was not started before');
            }
        }

        $task->name = $name;
        $task->save();
    }

    public function show(Request $request)
    {
        $task = Task::where(['name' => $request->task_name, 'end_time' => null, 'status' => 'Running'])->first();
        return $task;
    }
}
