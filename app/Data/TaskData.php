<?php

namespace App\Data;

use App\Enums\TaskStatus;
use App\Models\Task;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class TaskData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public ?string $description,
        public TaskStatus $status,
        public string $created_at,
    ) {}

    public static function fromModel(Task $task): self
    {
        return new self(
            id: $task->id,
            title: $task->title,
            description: $task->description,
            status: $task->status,
            created_at: $task->created_at->format('d/m/Y H:i'), // Já envias a data formatada para o utilizador
        );
    }
}
