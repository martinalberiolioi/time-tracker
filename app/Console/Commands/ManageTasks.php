<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use App\Http\Controllers\TaskController;

class ManageTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string $task_name
     * @var string $action
     */
    protected $signature = 'task:make {task_name} {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start or stop running a task. To create a task with more than one word (E.g.: drink water), use quotation marks (E.g.: "drink water")';

    /**
     * Execute the console command.
     * Receives a task name and the action to be performed.
     * 
     * Before starting a task, it checks it isn't running already.
     * Before ending a task, it checks it exists in the DB first.
     * 
     * @return string Informing the procedure was successfull or if there was any errors
     * @throws Error if the user tries to start a task that's already running
     * @throws Error if the user tries to stop a task that doesn't exist in the BD
     * @throws Error if the it receives an action different from "start" or "stop"
     */
    public function handle()
    {
        $success = false;

        $controller = new TaskController();

        $task_name = $this->argument('task_name');
        $action = $this->argument('action');
        
        $request = new Request();
        $request->task_name = $task_name;
        $request->action = $action;

        // Search the task in the DB
        $task = $controller->show($request);

        if(strtolower($action) == 'start') {
            if(!isset($task)) {
                $st = date('Y-m-d H:i:s');
                $st_milliseconds = strtotime($st) * 1000; // Converts date to milliseconds
    
                $request->start_time = $st_milliseconds;
                $success = true;
            } else {
                $this->error('The inserted task is already running');
            }
        } else if(strtolower($action) == 'end') {
            $task = $controller->show($request);
            
            if(isset($task)) {
                $et = date('Y-m-d H:i:s');
                $et_milliseconds = strtotime($et) * 1000; // Converts date to milliseconds
    
                $request->start_time = strtotime($task->start_time) * 1000;
                $request->end_time = $et_milliseconds;
                $success = true;
            } else {
                $this->error('The inserted task was not started before');
            }
        } else {
            $this->error('Action not supported. Try using [start] or [end]');
        }

        if($success) {
            $controller->store($request);
            $this->info("Task '" . $task_name . "' has been " . $action . "ed successfully");
        }
    }
}