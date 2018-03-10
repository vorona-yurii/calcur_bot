<?php

function calc_zp($zp){
    $result = $zp - 0.18 * $zp - 0.015 * $zp;

    return $result;
}