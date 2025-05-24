<?php

namespace App\Asana\APIs;

use App\Asana\DTOs\ProjectDTO;

trait ProjectApi
{
    private $projects = 'projects';

    public function getProjects(): array
    {
        $response = $this->http->get("{$this->baseUrl}{$this->projects}");

        $array = [];

        foreach (json_decode($response->body())->data as $data) {
            $array[] = new ProjectDTO(id: $data->gid, name: $data->name);
        }

        return $array;
    }

    public function createProject(ProjectDTO $project): ProjectDTO
    {
        $workspaces = $this->getWorkspaces();
        $teams = $this->getTeams($workspaces[0]->id);
        $response = $this->http->post("{$this->baseUrl}{$this->projects}", [
            'data' => [
                'name' => $project->name,
                'workspace' => $workspaces[0]->id,
                'team' => $teams[0]->id,
                'archived' => false,
                // 'color' => 'light-green',
                // 'notes' => '',
                // 'default_view' => 'list',
                // 'default_access_level' => 'admin',
                // 'minimum_access_level_for_customization' => 'admin',
                // 'minimum_access_level_for_sharing' => 'admin',
                // 'html_notes' => '',
                // Additional optional parameters with defaults can be added here
            ],
        ]);
        $data = json_decode($response->body())->data;
        return new ProjectDTO(id: $data->gid, name: $data->name);
    }
}
