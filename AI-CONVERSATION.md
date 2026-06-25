Build Challenge — PHP / Laravel Developer
Thanks for applying. This is a short build challenge. A senior Laravel developer using AI tooling should finish it in about 45 minutes.

You are encouraged to use Claude Code / Cursor — that is part of what we are evaluating. The one rule: you must commit your AI conversation (see step 4).

Stack (required)
PHP 8.3+
Laravel 11
Livewire 3
Volt (single-file components)
Pest (tests are already written for you)
MySQL
These are already installed in this template. Do not change versions.

The task
Build a single Volt single-file component that manages a task list, backed by a MySQL table through Eloquent.

It must:

List all tasks.
Create a task from a title input. An empty title must be rejected with a validation error and must not be saved.
Toggle a task between complete and incomplete.
Keep it simple and clean. We are reading your code, not grading visual design.

The contract (build to this exactly — the tests depend on it)
A migration creates a tasks table with these columns: id, title (string), completed (boolean, default false), created_at, updated_at.

A Task Eloquent model (App\Models\Task) with title and completed mass-assignable.

A Volt component named tasks (file: resources/views/livewire/tasks.blade.php) mounted at the route /tasks, with:

a public property $title (string),
a method addTask() that validates title as required|string|max:255, creates the task, and resets $title,
a method toggle($id) that flips that task's completed value.
The provided Pest suite (tests/Feature/TaskListTest.php) checks all of the above. Do not edit the test file.

Run the tests locally
composer install
cp .env.example .env
php artisan key:generate
# point .env at your local MySQL, then:
php artisan migrate
php artisan test
Green across the board = you have met the spec.

Submit
Click "Use this template" at the top of this repo to make your own copy. Keep it public.
Build the task. Commit as you go.
Commit your AI conversation as a file named AI-CONVERSATION.md at the repo root — paste the full conversation you had with Claude / Cursor while building this. This is required; an entry without it is incomplete.
Push. GitHub Actions runs the test suite automatically — you'll see a green check or red X on your latest commit.
Go back to the application form and paste your repository URL into the "Build challenge" field.
That's it. We review your code, your test result, and how you worked with AI, all from your repo. Good luck.

This challenge is deliberately small. If you're a Laravel developer, the reviewer is mainly checking:

Can you follow an exact specification?
Can you work with Laravel 11 + Livewire 3 + Volt?
Can you write clean code that passes tests?
Can you use AI effectively and document the process?
Expected files
Migration
database/migrations/xxxx_xx_xx_create_tasks_table.php
Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->boolean('completed')->default(false);
    $table->timestamps();
});
Model
app/Models/Task.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'completed',
    ];
}
Route
routes/web.php
use Livewire\Volt\Volt;

Volt::route('/tasks', 'tasks');
Volt Component
resources/views/livewire/tasks.blade.php

Laravel Volt single-file component:

<?php

use App\Models\Task;
use Livewire\Volt\Component;

new class extends Component
{
    public string $title = '';

    public function addTask(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create([
            'title' => $this->title,
        ]);

        $this->reset('title');
    }

    public function toggle($id): void
    {
        $task = Task::findOrFail($id);

        $task->update([
            'completed' => ! $task->completed,
        ]);
    }

    public function tasks()
    {
        return Task::latest()->get();
    }
};
?>

<div>
    <form wire:submit="addTask">
        <input
            type="text"
            wire:model="title"
            placeholder="Task title"
        >

        <button type="submit">
            Add Task
        </button>

        @error('title')
            <div>{{ $message }}</div>
        @enderror
    </form>

    <ul>
        @foreach($this->tasks() as $task)
            <li>
                <label>
                    <input
                        type="checkbox"
                        wire:click="toggle({{ $task->id }})"
                        @checked($task->completed)
                    >

                    {{ $task->title }}
                </label>
            </li>
        @endforeach
    </ul>
</div>
AI-CONVERSATION.md

Don't forget this requirement. Many candidates fail because they complete the code but forget:

AI-CONVERSATION.md

at the repository root.

Paste your full conversation with Claude/Cursor/ChatGPT while building the challenge.

Time estimate

For an experienced Laravel developer:

Task	Time
Migration	2 min
Model	1 min
Route	1 min
Volt component	15-20 min
Run tests & fix failures	10-15 min
AI-CONVERSATION.md	2 min
Commit & push	5 min

Total: ~30-45 minutes.

If you have not used Volt before, expect around 60-90 minutes.

One thing I'd verify before submitting is whether the tests expect:

public function tasks()

or

#[Computed]
public function tasks()

because some challenge authors write the Pest tests assuming a specific Volt pattern. Run:

php artisan test

and adjust only if the tests require it. The tests are the source of truth.