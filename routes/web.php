<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsanaController;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Http\Controllers\TelegramWebhookController;


Route::get('/', function () {
    return view('welcome');
});


Route::prefix('telegram')->group(function () {
    Route::get('/install-webhook', [TelegramWebhookController::class, 'installWebhook']);
    Route::post('/webhook', [TelegramWebhookController::class, 'webhook']);
});



Route::prefix('asana')->group(function () {
    Route::get('/install-webhook', [AsanaController::class, 'installWebhook']);
    Route::post('/webook', [AsanaController::class, 'webhook']);
});


// Route::get('/test', function () {

//         // $response = Telegram::setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL')]);

//     ;
//     // Telegram::bot()->sendMessage([
//     //     'chat_id' => env('LOAI_CHAT_ID'),
//     //     'text' => 'Webhook Started',
//     // ]);


//     return json_decode(Telegram::getWebhookInfo());
// });
