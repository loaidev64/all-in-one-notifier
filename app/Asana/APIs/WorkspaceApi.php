<?php

namespace App\Asana\APIs;

use App\Asana\DTOs\ProjectDTO;
use App\Asana\DTOs\WorkspaceDTO;

trait WorkspaceApi
{
    private $workspaces = 'workspaces';
    private $teams = 'teams';

    public function getWorkspaces(): array
    {
        $response = $this->http->get("{$this->baseUrl}{$this->workspaces}");

        $array = [];

        foreach (json_decode($response->body())->data as $data) {
            $array[] = new WorkspaceDTO(id: $data->gid, name: $data->name);
        }

        return $array;
    }

    public function getTeams(string $workspaceId): array
    {
        $response = $this->http->get("{$this->baseUrl}{$this->workspaces}/$workspaceId/{$this->teams}");

        $array = [];

        foreach (json_decode($response->body())->data as $data) {
            $array[] = new WorkspaceDTO(id: $data->gid, name: $data->name);
        }

        return $array;
    }
}
