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
    ["Калькулятор зарплаты","Процентный калькулятор"],
    ["SpeedБух", "Сайт"],
    ["Информация"]
]; //keyboard

$keyboard_for_calc = [
    ["Найти процент от числа", "Вычесть проценты от числа"],
    ["Прибавить проценты к числу", "Рост/ Падение (процентное изменение)"],
    ["Назад"]
]; //keyboard

$keyboard_home = [
    ["Домой"]
]; //keyboard

if($text){

    switch ($text){

        case '/start':{
            $reply = "Здравствуйте, на связи Ваш персональный  бухгалтер конструктор. Жду Ваш вопрос!";
            UserEvent($chat_id, 'Null', $name);
            $keyboard = $keyboard_main;
            break;
        }

        case 'Информация':{
            $reply = "Вывод текста";
            UserEvent($chat_id, 'Null', $name);
            $keyboard = $keyboard_main;
            break;
        }

        case 'SpeedБух':{
            $reply = "В разработке";
            UserEvent($chat_id, 'Null', $name);
            $keyboard = $keyboard_main;
            break;
        }

        case 'Сайт':{
            $reply = "<a href='http://buhconstructor.com'>buhconstructor.com</a>";
            UserEvent($chat_id, 'Null', $name);
            $keyboard = $keyboard_main;
            break;
        }

        case 'Домой':{

            $reply = "Выберите пунк";
            UserEvent($chat_id, 'Null', $name);
            $keyboard = $keyboard_main;
            break;
        }

        case "Процентный калькулятор":{
            $reply = "Выберите калькулятор";
            UserEvent($chat_id, 'OC', $name);
            $keyboard = $keyboard_for_calc;
            break;
        }

        case 'Калькулятор зарплаты':{
            $reply = "Введите начисленую зароботную плату";
            UserEvent($chat_id, 'ZP', $name);
            $keyboard = $keyboard_home;
            break;
        }

        case 'Назад':{

            $reply = "Выберите пунк";
            UserEvent($chat_id, 'Null', $name);
            $keyboard = $keyboard_main;
            break;
        }

        case 'Найти процент от числа':{
            $reply = "Введите % —...";
            UserEvent($chat_id, 'OC1', $name);
            $keyboard = $keyboard_for_calc;
            break;
        }

        case "Вычесть проценты от числа":{
            $reply = "Введите % —...";
            UserEvent($chat_id, 'OC2', $name);
            $keyboard = $keyboard_for_calc;
            break;
        }

        case "Прибавить проценты к числу":{
            $reply = "Введите % —...";
            UserEvent($chat_id, 'OC3', $name);
            $keyboard = $keyboard_for_calc;
            break;
        }

        case "Рост/ Падение (процентное изменение)":{
            $reply = "Введите первое значение —...";
            UserEvent($chat_id, 'OC4', $name);
            $keyboard = $keyboard_for_calc;
            break;
        }

        case (preg_match_all('/^[0-9]{1,9}[.,]?[0-9]*$/', $text) ? true : false):{

            switch (UserSelect($chat_id)){
                case 'ZP':{
                    $reply = "Зарплата к выплате работнику \"на руки\":  " .calc_zp($text). " грн.\nЧто бы посчитать еще раз, введите начисленую зароботную плату";
                    UserEvent($chat_id, 'ZP', $name);
                    break;
                }
                case 'OC1':{
                    $reply = "Введите число —...";
                    UserEvent($chat_id, 'OC1A.'. $text, $name);
                    break;
                }
                case (preg_match_all('/^OC1A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". $A[1] ."% от ". $text ." = ". calc_oc1($A[1], $text) ."\nЧто бы посчитать еще раз, введите % — ...";
                    UserEvent($chat_id, 'OC1', $name);
                    break;
                }
                case 'OC2':{
                    $reply = "Введите число —...";
                    UserEvent($chat_id, 'OC2A.'. $text, $name);
                    break;
                }
                case (preg_match_all('/^OC2A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". $A[1] ."% от числа ". $text ." = ". calc_oc2($A[1], $text) ."\nЧто бы посчитать еще раз, введите % — ...";
                    UserEvent($chat_id, 'OC2', $name);
                    break;
                }
                case 'OC3':{
                    $reply = "Введите число —...";
                    UserEvent($chat_id, 'OC3A.'. $text, $name);
                    break;
                }
                case (preg_match_all('/^OC3A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    $reply = "Ответ: ". $A[1] ."% к числу ". $text ." = ". calc_oc3($A[1], $text) ."\nЧто бы посчитать еще раз, введите % — ...";
                    UserEvent($chat_id, 'OC3', $name);
                    break;
                }
                case 'OC4':{
                    $reply = "Введите второе значение —...";
                    UserEvent($chat_id, 'OC4A.'. $text, $name);
                    break;
                }
                case (preg_match_all('/^OC4A[.]?[0-9]{1,9}/', UserSelect($chat_id)) ? true : false):{
                    $A = explode('.', UserSelect($chat_id));
                    if($text > $A[1]){
                        $reply = "Число увеличилось на ". calc_oc4($A[1], $text). "%\nЧто бы посчитать еще раз, введите первое число — ...";
                    }else{
                        $reply = "Число уменьшилось на ". calc_oc4($A[1], $text). "%\nЧто бы посчитать еще раз, введите первое число — ...";
                    }

                    UserEvent($chat_id, 'OC4', $name);
                    break;
                }

                default:{
                    $reply = "Данное действие некорректно. Мы совершенствуемся!\nЕсли Вам необходима дополнительная информация, обратитесь к Вашему персональному бухгалтеру.\nНажмите на ссылку @nameTelegram";
                    break;
                }
            }

            $keyboard = $keyboard_home;

            break;
        }

        default: {
            $reply = "Данное действие некорректно. Мы совершенствуемся!\nЕсли Вам необходима дополнительная информация, обратитесь к Вашему персональному бухгалтеру.\nНажмите на ссылку @nameTelegram";
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