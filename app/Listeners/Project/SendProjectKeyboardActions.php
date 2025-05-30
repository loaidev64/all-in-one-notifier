<?php

namespace App\Listeners\Project;

use App\Events\ProjectEvent;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectKeyboardActions
{

    const DisplayTasks = 'Display tasks';

    public static function actions(): array
    {
        return [
            SendProjectKeyboardActions::DisplayTasks,
        ];
    }

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
    public function handle(ProjectEvent $event): void
    {
        $telegram = Telegram::bot();
        Cache::put("selected_project_{$event->chatId}", $event->project, now()->addMinutes(5));
        $keyboard =  Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true);
        $row = [];
        foreach (SendProjectKeyboardActions::actions() as $action) {
            $row[] = Keyboard::button($action);
            if (count($row) == 2) {
                $keyboard->row($row);
                $row = [];
            }
        }
        if (!empty($row)) {
            $keyboard->row($row);
        }
        $telegram->sendMessage([
            'chat_id' => $event->chatId,
            'text' => "Choose actions for the project: *{$event->project->name}*",
            'reply_markup' => $keyboard,
            'parse_mode' => 'Markdown',
        ]);
    }
}
