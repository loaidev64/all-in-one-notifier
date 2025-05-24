<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramWebhookController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $updates = Telegram::bot()->getWebhookUpdate();
        // Extract the message object from the updates
        $message = $updates->getMessage();

        // Get the text of the message sent by the user
        $text = $message->getText();

        // Get the unique chat ID where the message was sent from
        $chatId = $message->getChat()->getId();

        // If the message text is '/start', send a welcome message back to the user
        if ($text === 'Hello') {
            $response = "2 Hellos";

            // Use the Telegram API to send the response message to the same chat
            Telegram::bot()->sendMessage([
                'chat_id' => $chatId,  // The ID of the chat to send the message to
                'text' => $response    // The message text to send
            ]);
        }

        return;
    }
}
