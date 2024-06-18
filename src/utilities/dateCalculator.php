<?php

namespace Cryptodorea\Woocryptodorea\utilities;

use Cryptodorea\Woocryptodorea\abstracts\utilities\dateCalculatorAbstract;

/**
 * calculate current time in the unix format
 * @param $date in Date Format
 */
class dateCalculator extends dateCalculatorAbstract
{

    public function currentDate()
    {

        return time();

    }

    /**
     * calculate unix date in Month Format
     * @param $date in Date Format
     */
    public function unixToMonth($time)
    {

        return $date = date('F', $time);

    }

    /**
     * calculate unix date in Day Format
     * @param $date in Date Format
     */
    public function unixToday($time)
    {

        return $date = date('d', $time);

    }

    /**
     * calculate unix time in the future date and time
     * @param $date in Date Format
     */
    public function futureDate($month, $day, $year)
    {

        return $date = mktime(0, 0, 0, $month, $day, $year); //e.g: 7 1 2000

    }

    /**
     * calculate expire date for campaign creation process
     */
    public function expDateCampaign($startDateMonth, $startDateDay, $expDate)
    {
        $monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $daysListCount = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];


        $remainedDays = $daysListCount[(int)$startDateMonth-1] - (int)$startDateDay;

        if($expDate === 'weekly'){
            if($remainedDays < 7){
                $exp = 7 - $remainedDays;

            }else{
                $exp = (int)$startDateDay + 7;

            }
        }


        if($exp < 9){
            $exp = '0' . $exp;
        }

        return (string)$exp;
    }

}