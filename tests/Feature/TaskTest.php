<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * Tests if an empty list of tasks is retrieved successfully
     */
    public function retrieve_empty_list_of_tasks()
    {
        $response = $this->getJson(route('tasks.apiListAllTasks'));

        $response->assertStatus(200)
                ->assertJson([]); // Assuming your application returns an empty array when no tasks are found.
    }

    /** @test
     * Tests if a list of tasks is retrieved successfully
     */
    public function retrieve_tasks()
    {
        // Create a couple of tasks in the database
        $taskOne = Task::create([
            'name' => 'Task 1',
            'completed' => 0,
        ]);

        $response = $this->getJson(route('tasks.apiListAllTasks'));

        $response->assertStatus(200)
                ->assertJsonFragment([
                    ['completed' => 0, 'id' => $taskOne->id, 'name' => 'Task 1'],
                ]);
    }

    /** @test
     * Tests if a task can be updated successfully
    */
    public function a_task_can_be_updated_successfully()
    {
        $task = Task::create([
            'name' => 'Original Task Name',
            'completed' => false,
        ]);

        $response = $this->putJson(route('tasks.update', $task->id), [
            'name' => 'Updated Task Name',
            'completed' => true,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Task updated successfully']);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name',
            'completed' => true,
        ]);
    }

    /** @test
     * Tests if updating a nonexistent task returns an error
    */
    public function update_missing_task()
    {
        $response = $this->putJson(route('tasks.update', 999), [
            'name' => 'Updated Task Name',
            'completed' => true,
        ]);

        $response->assertStatus(404); // Assuming your application returns a 404 for not found.
    }


}
