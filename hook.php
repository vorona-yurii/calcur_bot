<?php
require 'vendor/autoload.php';

require 'config.php';
require 'function.php';

use Telegram\Bot\Api;

session_start();

$telegram = new Api(BOT_API_KEY); //set api telegram bot

$result = $telegram -> getWebhookUpdates(); //get full information about message

$text = $result['message']['text']; //Text message
$chat_id = $result['message']['chat']['id']; //id user
$name = $result['message']['from']['username']; //Username

$keyboard = [
    ["Кал-тор зарплаты","Другие кал-ры"],
    ["SpeedБух", "Сайт"],
    ["Информация"]
]; //keyboard

if($text){

    switch ($text){
        case '/start':{
            $reply = "Добро пожаловать в бота!";

            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]);

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
                'reply_markup' => $reply_markup
            ]);

            break;
        }
        case 'Информация':{
            $reply = "Вывод текста";

            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]);

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
                'reply_markup' => $reply_markup
            ]);

            break;
        }
        case 'SpeedБух':{
            $reply = "В разработке";

            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => false
            ]);

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
                'reply_markup' => $reply_markup
            ]);

            break;
        }
        case 'Кал-тор зарплаты':{
            $reply = "Введите начисленую зароботную плату";

            $_SESSION['calc'] = '0';

            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
                'reply_markup' => $reply_markup
            ]);

            break;
        }
        case preg_match('/[^0-9,.]/', $text):{

            switch ($_SESSION['calc']){
                case 0:{
                    $reply = calc_zp($text);
                }
            }

            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
                'one_time_keyboard' => true
            ]);

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
                'reply_markup' => $reply_markup
            ]);

            break;
        }
        default: {
            $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'parse_mode'=> 'HTML',
                'text' => $reply
            ]);
        }
    }

}else{
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Отправьте текстовое сообщение."
    ]);
}

?>