<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;


Route::get('/', function () {
    return view('welcome');
});

Route::get('websocket/{service}', function () {
    Telegram::bot()->sendMessage([
        'chat_id' => env('LOAI_CHAT_ID'),
        'text' => 'some request came',
    ]);
    Log::debug(request());
    return;
});


Route::get('/test', function () {

    Telegram::bot()->sendMessage([
        'chat_id' => env('LOAI_CHAT_ID'),
        'text' => 'Hello World',
    ]);

    return 'success';
});
