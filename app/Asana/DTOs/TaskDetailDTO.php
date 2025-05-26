<?php

namespace App\Asana\DTOs;

class TaskDetailDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $id,
        public string $name,
        public bool $completed,
        public ?string $completedAt,
        public ?string $completedBy, // the name of the user that completed it
        public bool $liked,
        public string $notes,
        public int $likesCount,
        public int $subTasksCount,
        public ?string $assignee, // the name of the user
        // TODO:: add all of other fields
    ) {
        //
    }

    public function getCompletedStatus(): string
    {
        return $this->completed ? 'is completed'
            : 'is not completed yet';
    }

    public function completedText(): string
    {
        return $this->completed ? "
        \n completed: *{$this->getCompletedStatus()}*
        completed at: *{$this->completedAt}*
        completed by: *{$this->completedBy}*
        " : "";
    }

    public function likedText(): string
    {
        return $this->liked ? "
        \n number of likes: *{$this->likesCount}*
        " : "";
    }

    public function subTasksText(): string
    {
        return $this->subTasksCount > 0 ? "
        \n number of subtasks: *{$this->subTasksCount}*
        " : "";
    }

    public function assigneeText(): string
    {
        return $this->assignee != null ? "
        \n assignee: *{$this->assignee}*
        " : "";
    }
}
