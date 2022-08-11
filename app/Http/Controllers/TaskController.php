<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use DateTime;

class TaskController extends Controller
{
    /**
     * Returns all the tasks in the DB. Calculates total elapsed time on all tasks.
     * 
     * If there's a repeated task, it sums the elapsed time to get a total elapsed time.
     * 
     * @return View in case the method is called from a browser
     * @return Array in case the method is called from the terminal
     */
    public function index(Request $request)
    {
        $total_elapsed_time = 0;
        $tasks = Task::all();
        $results = [];
        
        foreach($tasks as $t) {
            $total_task_time = 0;

            // If the task is in the results array, skip the process to avoid duplicates
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

        if(isset($request->origin) && $request->origin == 'terminal') {
            return ['results' => $results, 'total_time' => $tt_parsed];
        }

        return view('home', ['results' => $results, 'total_time' => $tt_parsed]);
    }

    /**
     * Receives a task name, start time and the action to be performed.
     * 
     * Before starting a task, it checks it isn't running already.
     * Before ending a task, it checks it exists in the DB first.
     * 
     * @throws Error if the user tries to start a task that's already running
     * @throws Error if the user tries to stop a task that doesn't exist in the BD
     */
    public function store(Request $request)
    {
        $name = $request->task_name;

        // Parses time from milliseconds to date
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

    /**
     * Searches a running task by name
     * 
     * @return Task if found
     */
    public function show(Request $request)
    {
        $task = Task::where(['name' => $request->task_name, 'end_time' => null, 'status' => 'Running'])->first();
        return $task;
    }
}
