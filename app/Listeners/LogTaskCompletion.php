<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogTaskCompletion implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(TaskCompleted $event)
    {
        Log::info("Task {$event->task->id} completed at: " . now());
    }
}