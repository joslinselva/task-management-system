<?php

namespace App\Services;

use App\Events\TaskCompleted;
use App\Models\Task;
use App\Models\User;
use App\Jobs\SendTaskAssignedNotification;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class TaskService
{
    public function createTask(array $data): Task
    {
        try {
            return Task::create($data);
        } catch (Exception $e) {
            throw new Exception("Failed to create task: " . $e->getMessage());
        }
    }

    public function assignTask(Task $task, User $user): Task
    {
        try {
            $task->assigned_to = $user->id;
            $task->save();
            SendTaskAssignedNotification::dispatch($task, $user);
            return $task;
        } catch (Exception $e) {
            throw new Exception("Failed to assign task: " . $e->getMessage());
        }
    }

    public function completeTask(Task $task): Task
    {
        try {
            $task->status = 'completed';
            $task->save();
            event(new TaskCompleted($task));
            return $task;
        } catch (Exception $e) {
            throw new Exception("Failed to complete task: " . $e->getMessage());
        }
    }

    public function getTasks(array $filters = []): Collection
    {
        try {
            $query = Task::query();
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (isset($filters['assigned_to'])) {
                $query->where('assigned_to', $filters['assigned_to']);
            }
            if (isset($filters['due_date_from'])) {
                $query->whereDate('due_date', '>=', $filters['due_date_from']);
            }
            if (isset($filters['due_date_to'])) {
                $query->whereDate('due_date', '<=', $filters['due_date_to']);
            }
            return $query->get();
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve tasks: " . $e->getMessage());
        }
    }

    public function findTaskById(int $id): ?Task
    {
        try{
            return Task::find($id);
        } catch (Exception $e) {
            throw new Exception("Failed to find task: " . $e->getMessage());
        }
    }
}