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