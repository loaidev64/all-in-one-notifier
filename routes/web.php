<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\TelegramWebhookController;


Route::get('/', function () {
    return view('welcome');
});

Route::post('telegram/webhook', TelegramWebhookController::class);


Route::get('/test', function () {

    $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);

    Telegram::bot()->sendMessage([
        'chat_id' => env('LOAI_CHAT_ID'),
        'text' => 'Webhook Started',
    ]);


    return 'success';
});
