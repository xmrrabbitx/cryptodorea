<?php


/**
 * calculate current time in the unix format
 * @param $date in Date Format
 */
function currentDate(){

    return time();

 }

 /**
 * calculate unix date in Month Format
 * @param $date in Date Format
 */
function unixToMonth($time){

   return $date = date('F',$time);;

}

 /**
 * calculate unix date in Day Format
 * @param $date in Date Format
 */
function unixToday($time){

   return $date = date('d',$time);;

}

 /**
 * calculate unix time in the future date and time
 * @param $date in Date Format
 */
function futureDate($month, $day, $year){

   return $date = mktime(0, 0, 0, $month, $day, $year); //e.g: 7 1 2000

}