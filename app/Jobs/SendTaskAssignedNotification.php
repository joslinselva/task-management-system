<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\TaskAssigned;

class SendTaskAssignedNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $task;
    protected $user;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    public function handle()
    {
        Log::info("Send Job Task Started");
        try {
            Mail::to($this->user->email)->send(new TaskAssigned($this->task, $this->user));
            Log::info("Email sent successfully to {$this->user->email} for task {$this->task->id}.");
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$this->user->email} for task {$this->task->id}. Error: " . $e->getMessage());
        }
    }
}