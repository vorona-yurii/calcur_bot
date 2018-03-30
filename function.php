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
    $result = ($a * $b)/100;

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc2($a, $b){
    $result = $b - (($b/100) * $a);

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc3($a, $b){
    $result = $b + ((round($b)/100) * $a);

    return $result;
}

/**
 * @param $a
 * @param $b
 * @return float
 */
function calc_oc4($a, $b){
        $result = 100 * ((abs($b) - abs($a))/$a);

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
 * @param $last_event
 */
function UserEvent($user_id, $last_event){

    if(dbQuery("SELECT * FROM `users` WHERE user_id = '".$user_id."'")->fetch( PDO::FETCH_COLUMN ) == NULL) {
        dbQuery("INSERT INTO `users` (`user_id`, `last_event`, `date`) VALUES ('" . $user_id . "', '" . $last_event . "','". time() ."')");
    }else {
        $sql = "UPDATE `users` SET";

        if($last_event){
            $sql .= "`last_event` = '".$last_event."', ";
        }
        $sql .= "`date` = '". time() ."'";
        $sql .= " WHERE `user_id` = '". $user_id ."'";

        dbQuery($sql);
    }
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