<?php

namespace App\Listeners\Project;

use App\Asana\Client;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use App\Events\GetTasksForProjectEvent;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Contracts\Queue\ShouldQueue;

class GetTasksForProject
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(GetTasksForProjectEvent $event): void
    {
        $telegram = Telegram::bot();
        $asana = new Client;
        $tasks =
            Cache::remember("tasks_{$event->chatId}", now()->addMinutes(5), fn() => $asana->getTasks($event->project));
        $keyboard =  Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true);
        $row = [];
        foreach ($tasks as $task) {
            $row[] = Keyboard::button($task->name);
            if (count($row) == 2) {
                $keyboard->row($row);
                $row = [];
            }
        }
        $telegram->sendMessage([
            'chat_id' => $event->chatId,
            'text' => "Choose task:",
            'reply_markup' => $keyboard,
        ]);
    }
}
