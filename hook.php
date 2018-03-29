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

$keyboard_main = [
    ["Калькулятор зарплаты","Другие калькуляторы"],
    ["SpeedБух", "Сайт"],
    ["Информация"]
]; //keyboard

$keyboard_for_calc = [
    ["Сколько это А % от В", "А это сколько % от В"],
    ["А  это В % от скотльки ?", "Рост / Падение от А до В ?"],
    ["Назад"]
]; //keyboard

$keyboard_home = [
    ["Домой"]
]; //keyboard

if($text){

    switch ($text){

        case '/start':{
            $reply = "Здравствуйте, на связи Ваш персональный  бухгалтер конструктор. Жду Ваш вопрос!";

            UserEvent($chat_id, 'Null');

            $keyboard = $keyboard_main;

            break;
        }

        case 'Информация':{
            $reply = "Вывод текста";

            UserEvent($chat_id, 'Null');

            $keyboard = $keyboard_main;

            break;
        }

        case 'SpeedБух':{
            $reply = "В разработке";

            UserEvent($chat_id, 'Null');

            $keyboard = $keyboard_main;

            break;
        }

        case 'Сайт':{
            $reply = "<a href='http://buhconstructor.com'>buhconstructor.com</a>";

            UserEvent($chat_id, 'Null');

            $keyboard = $keyboard_main;

            break;
        }

        case 'Домой':{

            $reply = "Выберите пунк";

            UserEvent($chat_id, 'Null');

            $keyboard = $keyboard_main;

            break;
        }

        case 'Другие калькуляторы':{
            $reply = "Процентный калькулятор - Как найти процент от числа?\nВыберите калькулятор";

            UserEvent($chat_id, 'OC');

            $keyboard = $keyboard_for_calc;

            break;
        }

        case 'Калькулятор зарплаты':{
            $reply = "Введите начисленую зароботную плату";

            UserEvent($chat_id, 'ZP');

            $keyboard = $keyboard_home;

            break;
        }

        case 'Назад':{

            $reply = "Выберите пунк";

            UserEvent($chat_id, 'Null');

            $keyboard = $keyboard_main;

            break;
        }

        case 'Сколько это А % от В':{
            $reply = "Введите число <b>А</b>";

            UserEvent($chat_id, 'OC1');

            $keyboard = $keyboard_for_calc;

            break;
        }

        case "А это сколько % от В":{
            $reply = "Введите число <b>А</b>";

            UserEvent($chat_id, 'OC2');

            $keyboard = $keyboard_for_calc;

            break;
        }

        case "А  это В % от скотльки ?":{
            $reply = "Введите число <b>А</b>";

            UserEvent($chat_id, 'OC3');

            $keyboard = $keyboard_for_calc;

            break;
        }

        case "Рост / Падение от А до В ?":{
            $reply = "Введите число <b>А</b>";
            UserEvent($chat_id, 'OC4');
            $keyboard = $keyboard_for_calc;

            break;
        }

        case (preg_match_all('/^[0-9]{1,9}[.,]?[0-9]*$/', $text) ? true : false):{

            switch (UserSelect($chat_id)){
                case 'ZP':{
                    $reply = "Зарплата к выплате работнику \"на руки\":  " .calc_zp($text). " грн";
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC1':{
                    $reply = "Введите число <b>B</b>";
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
                    $reply = "Введите число <b>B</b>";
                    UserEvent($chat_id, 'OC2A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC2A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc2($A[1], $text). "%";
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC3':{
                    $reply = "Введите число <b>B</b>";
                    UserEvent($chat_id, 'OC3A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC3A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc3($A[1], $text);
                    UserEvent($chat_id, 'Null');
                    break;
                }
                case 'OC4':{
                    $reply = "Введите число <b>B</b>";
                    UserEvent($chat_id, 'OC4A.'. $text);
                    break;
                }
                case (preg_match_all('/^OC4A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". calc_oc4($A[1], $text). "%";
                    UserEvent($chat_id, 'Null');
                    break;
                }

                default:{
                    $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
                    break;
                }
            }

            $keyboard = $keyboard_home;

            break;
        }

        default: {
            $reply = "По запросу \"<b>".$text."</b>\" ничего не найдено.";
            $keyboard = false;
        }
    }

    if($keyboard){
        $reply_markup = $telegram->replyKeyboardMarkup([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ]);

        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $reply,
            'parse_mode'=> 'HTML',
            'reply_markup' => $reply_markup
        ]);
    }else {
        $telegram->sendMessage([
            'chat_id' => $chat_id,
            'text' => $reply,
            'parse_mode' => 'HTML'
        ]);
    }

}else{
    $telegram->sendMessage([
        'chat_id' => $chat_id,
        'text' => "Отправьте текстовое сообщение."
    ]);
}

?>