<?php

namespace App\Utile;

use Illuminate\Support\Facades\Log;

class Telegram
{
    private static string $botToken        = '';
    private static string $getChatIdApiURL = 'https://api.telegram.org/bot{{BOT_TOKEN}}/getUpdates';
    private static string $sendMsgApiURL   = 'https://api.telegram.org/bot{{BOT_TOKEN}}/sendmessage';

    const CHAT_ID_LIST = [
        'emop99' => '53000314',
    ];

    private static function setting(): void
    {
        self::$botToken        = env('TELEGRAM_BOT_TOKEN', '');
        self::$getChatIdApiURL = self::urlTokenReplace(self::$getChatIdApiURL);
        self::$sendMsgApiURL   = self::urlTokenReplace(self::$sendMsgApiURL);
    }

    private static function urlTokenReplace(string $url): string
    {
        return str_replace('{{BOT_TOKEN}}', self::$botToken, $url);
    }

    public static function sendCurl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $resultData = curl_exec($ch);
        $error      = curl_error($ch);

        if ($error) {
            Log::channel('telegram')->error(__METHOD__ . '::' . $error);
        }

        if ($resultData) {
            return json_decode($resultData, true);
        } else {
            return [];
        }
    }

    /**
     * 전체 유저에게 Send
     *
     * @param string $text
     * @return void
     */
    public static function allUserSendMsg(string $text): void
    {
        if (env('TELEGRAM_NOTICE_USE', 0)) {
            self::setting();
            foreach (self::CHAT_ID_LIST as $chatId) {
                self::sendCurl(self::$sendMsgApiURL . '?chat_id=' . $chatId . '&text=' . urlencode($text));
            }
        }
    }
}
