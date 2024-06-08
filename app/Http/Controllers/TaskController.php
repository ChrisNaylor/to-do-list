<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a list of tasks.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $tasks = Task::all(); // Fetch all tasks from the database using Eloquent

        return view('tasks', ['tasks' => $tasks]); // Pass the tasks to the view
    }

    /**
     * Save new tasks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {

        $task = new Task;
        $task->name = $request->name;
        $task->save();

        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:255',
                'completed' => 'required|boolean',
            ]);

            $task = Task::findOrFail($id);

            if ($request->has('name')) {
                $task->name = $validatedData['name'];
            }

            if ($request->has('completed')) {
                $task->completed = $validatedData['completed'];
            }

            $task->save();

            return response()->json(['message' => 'Task updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error updating task: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json(['message' => 'Task deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting task: ' . $e->getMessage()], 500);
        }
    }
}
