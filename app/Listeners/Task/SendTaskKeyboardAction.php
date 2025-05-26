<?php

namespace App\Listeners\Task;

use App\Asana\Client;
use App\Events\TaskEvent;
use App\Asana\DTOs\TaskDetailDTO;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\InteractsWithQueue;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskKeyboardAction
{
    const GetSubTasks = 'Get subtasks';
    const MarkAsDone = 'Mark as done';

    public static function actions(TaskDetailDTO $task): array
    {
        $actions = [
            SendTaskKeyboardAction::GetSubTasks,
        ];
        if (!$task->completed) {
            $actions[] = SendTaskKeyboardAction::MarkAsDone;
        }

        return $actions;
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
    public function handle(TaskEvent $event): void
    {
        $asana = new Client;
        $telegram = Telegram::bot();
        Cache::put("selected_task_{$event->chatId}", $task = $asana->getTaskDetails($event->task), now()->addMinutes(5));
        $keyboard =  Keyboard::make()
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true);
        $row = [];
        foreach (SendTaskKeyboardAction::actions($task) as $action) {
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
            'text' => "
            Choose actions for the task:
            Task details:
            name: *{$task->name}*
            notes: *{$task->notes}* {$task->completedText()} {$task->likedText()} {$task->assigneeText()}
            ",
            'reply_markup' => $keyboard,
            'parse_mode' => 'Markdown',
        ]);
    }
}
