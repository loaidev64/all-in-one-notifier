<?php

namespace App\Http\Controllers;

use App\Events\ProjectEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Telegram\Commands\StartCommand;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramWebhookController extends Controller
{
    public function installWebhook()
    {
        Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);
    }

    /**
     * Handle the incoming request.
     */
    public function webhook(Request $request)
    {
        $updates = Telegram::bot()->getWebhookUpdate();
        $message = $updates->getMessage();
        $text = $message->getText();
        $chatId = $message->getChat()->getId();

        // First, process known commands
        Telegram::bot()->commandsHandler(true);

        Log::debug("The message is $text");

        // Early return if it's a command like /start or /projects
        if (str_starts_with($text, '/')) {
            return;
        }

        // Now check if the message text matches a project name
        $asana = new \App\Asana\Client;
        $projects = Cache::remember("projects_$chatId", now()->addMinutes(5), fn() => $asana->getProjects());

        $matchedProject = collect($projects)->firstWhere('name', $text);

        if ($matchedProject) {
            event(new ProjectEvent($matchedProject, $chatId));
        }

        return;
    }
}
