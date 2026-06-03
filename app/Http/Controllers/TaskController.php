<?php

namespace App\Http\Controllers;

use App\Data\TaskData;
use App\Enums\TaskStatus;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Inertia\Inertia;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::where('status', TaskStatus::INBOX)->latest();

        return Inertia::render('Tasks', [
            'tasks' => Inertia::scroll(fn() => TaskData::collect($tasks->paginate())),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        Task::create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tarefa adicionada com sucesso!')]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tarefa atualizada com sucesso!')]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Tarefa deletada com sucesso!')]);

        return back();
    }
}
