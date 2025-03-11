<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use App\Services\AuthService;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    protected $taskService,$authService;

    public function __construct(TaskService $taskService, AuthService $authService)
    {
        $this->taskService = $taskService;
        $this->authService = $authService;
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'status' => 'nullable|in:pending,completed,expired',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $task = $this->taskService->createTask($validator->validated());
            return response()->json([
                'message' => 'Task created successfully.',
                'data' => $task,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to create task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function assign(Request $request, int $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'assigned_to' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $task = $this->taskService->findTaskById($id);
            $user = $this->authService->findUserById($validator->validated()['assigned_to']);

            if (!$task) {
                return response()->json(['message' => 'Task not found.'], 404);
            }

            if (!$user) {
                return response()->json(['message' => 'User not found.'], 404);
            }

            $task = $this->taskService->assignTask($task, $user);
            return response()->json([
                'message' => 'Task assigned successfully.',
                'data' => $task,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Task or User not found.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to assign task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function complete(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->findTaskById($id);

            if (!$task) {
                return response()->json(['message' => 'Task not found.'], 404);
            }

            $task = $this->taskService->completeTask($task);
            return response()->json([
                'message' => 'Task marked as completed.',
                'data' => $task,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Task not found.'], 404);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to complete task.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:pending,completed,expired',
            'assigned_to' => 'nullable|exists:users,id',
            'due_date_from' => 'nullable|date',
            'due_date_to' => 'nullable|date|after_or_equal:due_date_from',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $tasks = $this->taskService->getTasks($validator->validated());

            if ($tasks->isEmpty()) {
                return response()->json([
                    'message' => 'No tasks found.',
                    'data' => [],
                ], 200);
            }

            return response()->json([
                'message' => 'Tasks retrieved successfully.',
                'data' => $tasks,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve tasks.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}