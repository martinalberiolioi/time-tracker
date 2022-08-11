<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;

class ShowTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns all the tasks with their status, start time, end time and total elapsed time';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request = new Request();
        $request->origin = 'terminal';

        $controller = new TaskController();
        $response = $controller->index($request);

        if(!empty($response['results'] && !empty($response['total_time']))) {
            $tasks = $response['results'];
            $total_time = $response['total_time'];

            $this->table(
                ['TASK NAME', 'START TIME', 'END TIME', 'TOTAL TASK TIME', 'STATUS'],
                $tasks
            );

            $this->newLine(2);
            $this->info('Total spent time: ' . $total_time);
        } else {
            $this->error('No tasks were found');
        }
    }
}
