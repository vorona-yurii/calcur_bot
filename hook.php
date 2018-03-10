<?php
require 'vendor/autoload.php';

require 'config.php';
require 'function.php';

use Telegram\Bot\Api;

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

$keyboard_for_calc = [
    ["1","2"],
    ["3", "4"]
]; //keyboard

if($text){

    switch ($text){

        case '/start':{
            $reply = "Привет, на связи чат бот бухгалтер конструктор";

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

        case 'Сайт':{
            $reply = "Сайт";

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

        case 'Другие кал-ры':{
            $reply = "Процентный калькулятор - Как найти процент от числа?";

            UserEvent($chat_id, 'OC');

            $reply_markup = $telegram->replyKeyboardMarkup([
                'keyboard' => $keyboard_for_calc,
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

            UserEvent($chat_id, 'ZP');

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

        case (preg_match_all('/^[0-9]{1,9}[.,]?[0-9]*$/', $text) ? true : false):{

            switch (UserSelect($chat_id)){
                case 'ZP':{
                    $reply = "Зарплата к выплате работнику \"на руки\":  " .calc_zp($text). " грн";
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC':{
                    switch ($text){
                        case 1: {
                            $reply = "Сколько это <code>A</code>% от <code>B</code>?/n. Введите число А";
                            UserEvent($chat_id, 'OC1');
                            break;
                        }
                        case 2: {
                            $reply = "2. Введите число А";
                            UserEvent($chat_id, 'OC2');
                            break;
                        }
                        case 3: {
                            $reply = "3. Введите число А";
                            UserEvent($chat_id, 'OC3');
                            break;
                        }
                        case 4: {
                            $reply = "4. Введите число А";
                            UserEvent($chat_id, 'OC4');
                            break;
                        }
                    }
                    break;
                }
                case 'OC1':{
                    $reply = "Введите число B";
                    UserEvent($chat_id, 'OC1A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC1A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc1($A[1], $text);
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC2':{
                    $reply = "Введите число B";
                    UserEvent($chat_id, 'OC1A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC2A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc2($A[1], $text);
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC3':{
                    $reply = "Введите число B";
                    UserEvent($chat_id, 'OC1A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC3A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc3($A[1], $text);
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC4':{
                    $reply = "Введите число B";
                    UserEvent($chat_id, 'OC1A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC4A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc4($A[1], $text);
                    UserEvent($chat_id, 'Null');
                    break;
                }

                default:{
                    $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
                    break;
                }
            }

//            $reply_markup = $telegram->replyKeyboardMarkup([
//                'keyboard' => $keyboard,
//                'resize_keyboard' => true,
//                'one_time_keyboard' => false
//            ]);

            $telegram->sendMessage([
                'chat_id' => $chat_id,
                'text' => $reply,
               // 'reply_markup' => $reply_markup
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