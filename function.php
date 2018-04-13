<?php

require 'config.php';
/**
 * @param $zp
 * @return float
 */
function calc_zp($zp){
    $result = $zp - 0.18 * $zp - 0.015 * $zp;

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc1($a, $b){
    $result = round(($a * $b)/100, 2);

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc2($a, $b){
    $result = round($b - (($b/100) * $a), 2);

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc3($a, $b){
    $result = round($b + ((round($b)/100) * $a), 2);

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc4($a, $b){
    if($b > $a){
        $result = round(100 * ((abs($b) - abs($a))/$a), 2);
    }else{
        $result = round(100 * ((abs($a) - abs($b))/$b), 2);
    }


    return $result;
}

/**
 * @param $data
 * @param int $lastInsertId
 * @return PDOStatement|string
 */
function dbQuery($data, $lastInsertId = 0){

    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_TABLE;
    $dbo = new PDO( $dsn, DB_USER, DB_PASS);
    $dbo -> exec("SET NAMES utf8mb4");
    $dbo -> exec("SET CHARACTER SET utf8mb4_general_ci");
    $dbo -> exec("SET SESSION collation_connection = utf8mb4_general_ci");
    $result = $dbo->prepare( $data );
    $result->execute();

    if($lastInsertId){
        return $dbo->lastInsertId();
    }

    return $result;
}
/**
 * @return mixed
 */
function GetFullUser()
{
    $result =  dbQuery("SELECT * FROM `users`")->fetchAll( PDO::FETCH_ASSOC );

    return $result;
}
/**
 * @param $user_id
 * @return mixed
 */
function UserSelect($user_id)
{
   $result =  dbQuery("SELECT * FROM `users` WHERE user_id = '".$user_id."'")->fetch( PDO::FETCH_ASSOC );

   return $result['last_event'];
}

/**
 * @param $user_id
 * @return mixed
 */
function UserSelectId($user_id)
{
    $result =  dbQuery("SELECT * FROM `users` WHERE user_id = '".$user_id."'")->fetch( PDO::FETCH_ASSOC );

    return $result;
}

/**
 * @param $user_id
 * @param $last_event
 * @param string $name
 */
function UserEvent($user_id, $last_event, $name = ''){

    if(dbQuery("SELECT * FROM `users` WHERE user_id = '".$user_id."'")->fetch( PDO::FETCH_COLUMN ) == NULL) {
        dbQuery("INSERT INTO `users` (`user_id`, `last_event`, `date`, `name`) VALUES ('" . $user_id . "', '" . $last_event . "','". time() ."','". $name ."')");
    }else {
        $sql = "UPDATE `users` SET";

        if($last_event){
            $sql .= "`last_event` = '".$last_event."', ";
        }
        $userSelect = UserSelectId($user_id);
        if(!empty($name) && $userSelect['name'] === NULL){
            $sql .= "`name` = '".$name."', ";
        }
        $sql .= "`date` = '". time() ."',";
        $sql .= "`lastnotif` = '0'";
        $sql .= " WHERE `user_id` = '". $user_id ."'";

        dbQuery($sql);
    }
}

/**
 * @param $user_id
 * @param $lastnotif
 */
function LastNotif($id, $lastnotif)
{
    $result =  dbQuery("UPDATE `users` SET `lastnotif` = '".$lastnotif."' WHERE `id` = '". $id ."'");
}

/**
 * @param $key
 * @return mixed
 */
function getSettings($key){

    $result =  dbQuery("SELECT * FROM `settings` WHERE `key` = '".$key."'")->fetch( PDO::FETCH_ASSOC );

    return $result['value'];
}

/**
 * @param $key
 * @param $value
 */
function setSettings($key, $value){

    if(dbQuery("SELECT * FROM `settings` WHERE `key` = '".$key."'")->fetch( PDO::FETCH_COLUMN ) == NULL) {
        dbQuery("INSERT INTO `settings` (`key`, `value`) VALUES ('" . $key . "', '" . $value . "')");
    }else {
        $sql = "UPDATE `settings` SET";

        if($value){
            $sql .= "`value` = '".$value."'";
        }
        $sql .= " WHERE `key` = '". $key ."'";

        dbQuery($sql);
    }
}