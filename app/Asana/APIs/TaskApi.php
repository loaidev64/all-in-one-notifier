<?php

namespace App\Asana\APIs;

use Carbon\Carbon;
use App\Asana\DTOs\TaskDTO;
use App\Asana\DTOs\ProjectDTO;
use App\Asana\DTOs\TaskDetailDTO;
use Illuminate\Support\Facades\Log;

trait TaskApi
{
    private $tasks = 'tasks';

    public function getTasks(ProjectDTO $project): array
    {
        $response = $this->http->get("{$this->baseUrl}{$this->projects}/{$project->id}/{$this->tasks}?limit=100");

        $array = [];

        foreach (json_decode($response->body())->data as $data) {
            $array[] = new TaskDTO(id: $data->gid, name: $data->name);
        }

        return $array;
    }

    public function getTaskDetails(TaskDTO $task): TaskDetailDTO
    {
        $response =
            $this->http
            ->get("{$this->baseUrl}{$this->tasks}/{$task->id}?opt_fields=gid,name,completed,completed_at,completed_by,completed_by.name,liked,notes,num_likes,assignee,assignee.name,num_subtasks");

        $data = json_decode($response->body())->data;
        $details = new TaskDetailDTO(
            id: $data->gid,
            name: $data->name,
            completed: $data->completed,
            completedAt: $data->completed_at != null ? Carbon::parse($data->completed_at)->toDateString() . ' ' . Carbon::parse($data->completed_at)->toTimeString() : null,
            completedBy: null,
            liked: $data->liked,
            notes: $data->notes,
            likesCount: $data->num_likes,
            subTasksCount: $data->num_subtasks,
            assignee: $data->assignee?->name,
        );

        if (isset($data->completed_by)) {
            $details->completedBy = $data->completed_by?->name;
        }

        return $details;
    }
}
