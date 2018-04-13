#!/usr/bin/php
<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 13.04.2018
 * Time: 1:19
 */

require 'vendor/autoload.php';
require 'config.php';
require 'function.php';

//$users = GetFullUser();
$user = [
    'id' => '1',
    'user_id' => '384607648',
    'lastnotif' => 'NULL',
    'name' => 'Yuv'
];
$sms = "Здравтсвуйте, %name%! На связи Бухгалтер конструктор \xF0\x9F\x98\x8E!\nМы с вами уже %time% не общались.\nЯ соскучился! Я всегда готов Вам помочь упростить Вашу работу!";
use Telegram\Bot\Api;

$telegram = new Api(BOT_API_KEY); //set api telegram bot

$telegram->sendMessage([
    'chat_id' => $user['user_id'],
    'text' => "Привет!\xF0\x9F\x98\x8E",
    'parse_mode'=> 'HTML'
]);

//
//foreach ($users as $user){
//    $datetime1 = new DateTime("Now");
//    $datetime2 = new DateTime(date('d-m-Y', $user['date']));
//    $interval = $datetime1->diff($datetime2);
//
//    $diff_days = $interval->format('%a');
//    $reply = false;
//
//    if($user['lastnotif'] === 'NULL'){
//        switch ($diff_days){
//            case 3:{
//                LastNotif($user['id'], 3);
//                $reply = "3 дня";
//                break;
//            }
//            case 7:{
//                LastNotif($user['id'], 7);
//                $reply = "7 дней";
//                break;
//            }
//            case (($diff_days % 30 == 0) ? true : false):{
//                LastNotif($user['id'], $diff_days);
//                $reply = "30 дней";
//                break;
//            }
//        }
//    }
//
//    if($diff_days == 3 && $user['lastnotif'] == 0){
//        LastNotif($user['id'], 3);
//        $reply = "3 дня";
//    }elseif($diff_days == 7 && $user['lastnotif'] == 3){
//        LastNotif($user['id'], 7);
//        $reply = "7 дней";
//    }elseif($diff_days == 30 && $user['lastnotif'] == 7){
//        LastNotif($user['id'], 30);
//        $reply = "30 дней";
//    }elseif(($diff_days % 30 == 0) && $diff_days != 30 && ($user['lastnotif'] == ($diff_days - 30))){
//        LastNotif($user['id'], $diff_days);
//        $reply = $diff_days." дней";
//    }
//
//    if($reply){
//        $array_str = [
//            '%name%' => $user['name'],
//            '%time%' => $reply
//        ];
//        $reply =  strtr($sms, $array_str);
//
//        $telegram->sendMessage([
//            'chat_id' => $user['user_id'],
//            'text' => $reply,
//            'parse_mode'=> 'HTML'
//        ]);
//    }
//}
