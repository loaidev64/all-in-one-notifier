<?php

namespace App\Telegram\Commands;

use App\Asana\Client;
use App\Asana\DTOs\ProjectDTO;
use Telegram\Bot\Commands\Command;
use Illuminate\Support\Facades\Cache;

class CreateProjectCommand extends Command
{
    protected string $name = 'create_project';
    protected string $description = 'Create a new Asana project';
    protected string $pattern = '{name}';

    public function handle()
    {
        $asana = new Client;
        $name = $this->argument('name');
        if (empty($name)) {
            $this->replyWithMessage([
                'text' => 'you should add a name',
            ]);
            return;
        }
        $project = new ProjectDTO(id: null, name: $name);
        $project = $asana->createProject($project);
        $this->replyWithMessage([
            'text' => "
            a new project was created with info : 
            id: {$project->id},
            name: {$project->name}
                ",
        ]);
        Cache::forget("projects_{$this->getUpdate()->getMessage()->getChat()->getId()}");
    }
}
