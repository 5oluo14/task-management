<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Js;

class TaskController extends Controller
{
    /**
     * @var TaskRepositoryInterface
     */
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function index(Request $request): JsonResponse
    {
        $status = $request->query('status');
        $tasks = $this->taskRepository->getAllByUser(Auth::id(), $status);

        return response()->json(new TaskCollection($tasks), 200);
    }


    public function store(StoreTaskRequest $request):JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $task = $this->taskRepository->create($data);

        return response()->json(['data' => new TaskResource($task), 'status' => 200], 201);
    }

    public function show(Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['data' => new TaskResource($task), 'status' => 200], 200);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $task = $this->taskRepository->update($task->id, $request->validated());

        return response()->json(['data' => new TaskResource($task), 'status' => 200], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->taskRepository->delete($task->id);

        return response()->json(['data' => 'Task deleted successfully', 'status' => 200], 200);
    }
}
