<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ExpireOverdueTasks extends Command
{
    protected $signature = 'tasks:expire-overdue';
    protected $description = 'Marks overdue pending tasks as expired.';

    public function handle()
    {
        Log::info("ExpireOverdueTasks command started.");

        try {
            $now = Carbon::now();
            Log::info("Current time: " . $now);

            $tasks = Task::where('due_date', '<', $now)
                ->where('status', 'pending')
                ->get();

            Log::info("Found " . $tasks->count() . " overdue pending tasks.");

            foreach ($tasks as $task) {
                try {
                    $task->status = 'expired';
                    $task->save();
                    Log::info("Task {$task->id} marked as expired.");
                    $this->info("Task {$task->id} marked as expired.");
                } catch (\Exception $taskException) {
                    Log::error("Failed to expire task {$task->id}. Error: " . $taskException->getMessage());
                    $this->error("Failed to expire task {$task->id}. Check logs.");
                }
            }

            Log::info("Overdue task expiration check completed.");
            $this->info("Overdue task expiration check completed.");
        } catch (\Exception $e) {
            Log::error("ExpireOverdueTasks command failed. Error: " . $e->getMessage());
            $this->error("ExpireOverdueTasks command failed. Check logs.");
        }
    }
}