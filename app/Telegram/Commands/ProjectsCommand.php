<?php

namespace App\Telegram\Commands;

use App\Asana\Client;
use Telegram\Bot\Commands\Command;

class ProjectsCommand extends Command
{
    protected string $name = 'projects';
    protected string $description = 'Get Asana projects';

    public function handle()
    {
        $asana = new Client;
        $projects = $asana->getProjects();
        foreach ($projects as $key => $project) {
            $this->replyWithMessage([
                'text' => $key + 1 . ". {$project->name}",
            ]);
        }
    }
}
