<?php

namespace App\Http\Controllers;

use App\Utile\Telegram;
use Illuminate\Support\Facades\Log;

trait ErrorTrait
{
    /**
     * Exception Error Process Function
     *
     * - Telegram Message Send
     * - Error Log Write
     *
     * @param string $logWriteMsg
     * @param string $telegramSendMsg
     * @return void
     */
    public function apiExceptionProc(string $logWriteMsg, string $telegramSendMsg): void
    {
        Telegram::allUserSendMsg($telegramSendMsg);
        Log::error($logWriteMsg);
    }
}
