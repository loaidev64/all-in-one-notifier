<?php

namespace App\Telegram\Commands;

use App\Asana\Client;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;

class ProjectsCommand extends Command
{
    protected string $name = 'projects';
    protected string $description = 'Get Asana projects';

    public function handle()
    {
        $asana = new Client;
        $projects = Cache::remember("projects_{$this->getUpdate()->getMessage()->getChat()->getId()}", now()->addMinutes(5), fn() => $asana->getProjects());
        $keyboard =  Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true);
        $row = collect();
        foreach ($projects as $key => $project) {
            $row->add(Keyboard::button($project->name));
            if ($row->count() == 2) {
                $keyboard = $keyboard->row($row->toArray());
                $row = collect();
            }
        }
        if ($row->isNotEmpty()) {
            $keyboard = $keyboard->row($row->toArray());
        }
        $this->replyWithMessage([
            'text' => 'Choose project',
            'reply_markup' => $keyboard,
        ]);
    }
}
