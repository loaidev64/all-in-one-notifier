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
            ->setOneTimeKeyboard(true)
            ->row([
                Keyboard::button('Display tasks'),
            ]);
        $telegram->sendMessage([
            'chat_id' => $event->chatId,
            'text' => "Choose actions for the project: *{$event->project->name}*",
            'reply_markup' => $keyboard,
            'parse_mode' => 'Markdown',
        ]);
    }
}
