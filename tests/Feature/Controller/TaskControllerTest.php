<?php

namespace Tests\Feature\Controller;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Override;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    #[Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    #[Test]
    public function createTask()
    {
        $body = [
            'title' => 'A title',
            'status' => 'inbox',
        ];

        $response = $this->post(route('tasks.store'), $body);

        $response->assertRedirectBack();

        $this->assertDatabaseHas(
            'tasks',
            [
                'title' => 'A title',
                'status' => 'inbox',
            ]
        );
    }

    #[Test]
    public function not_show_others_tasks()
    {
        Task::factory()->for(User::factory())->create();

        $response = $this->get(route('tasks.index'));

        $response->assertInertia(function (Assert $page) {
            return $page->where('tasks.total', 0);
        });
    }

    #[Test]
    public function show_your_tasks()
    {
        Task::factory()->for($this->user)->create();

        $response = $this->get(route('tasks.index'));

        $response->assertInertia(fn(Assert $page) => $page->where('tasks.total', 1));
    }

    #[Test]
    public function edit_task()
    {
        $task = Task::factory()->for($this->user)->create(
            [
                'title' => 'A title',
                'description' => 'A description',
                'status' => 'inbox',
            ]
        );

        $this->patch(
            route('tasks.update', $task->id),
            [
                'title' => 'Edited Title',
                'description' => 'Edited description',
                'status' => 'done',
            ]
        );


        $this->assertDatabaseMissing(
            'tasks',
            [
                'title' => 'A title',
                'description' => 'A description',
                'status' => 'inbox',
            ]
        );

        $this->assertDatabaseHas(
            'tasks',
            [
                'title' => 'Edited Title',
                'description' => 'Edited description',
                'status' => 'done',
            ]
        );
    }

    #[Test]
    public function delete_task()
    {
        $task = Task::factory()->for($this->user)->create();

        $this->delete(route('tasks.destroy', $task->id));

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
