<?php

namespace Cryptodorea\DoreaCashback\utilities;

use Cryptodorea\DoreaCashback\abstracts\utilities\dateCalculatorAbstract;

/**
 * calculate current time in the unix format
 * @param $date in Date Format
 */
class dateCalculator extends dateCalculatorAbstract
{

    public function currentDate()
    {

        //return time();
        return current_time('timestamp');

    }

    /**
     * calculate unix date in Month Format
     * @param $date in Date Format
     */
    public function unixToYear($time)
    {

        return gmdate('Y', $time);

    }

    /**
     * calculate unix date in Month Format
     * @param $date in Date Format
     */
    public function unixToMonth($time)
    {

        return gmdate('F', $time);

    }

    /**
     * calculate unix date in Day Format
     * @param $date in Date Format
     */
    public function unixToday($time)
    {

        return gmdate('d', $time);

    }

    /**
     * calculate expire date for campaign creation process
     */
    public function expDateCampaign($startDateDay, $startDateMonth, $startDateYear, $expDate):array
    {

        $monthsList = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $daysListCount = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        $remainedDays = $daysListCount[(int)$startDateMonth-1] - (int)$startDateDay;

        if($expDate === 'weekly'){
            if($remainedDays < 7){
                $expDay = 7 - $remainedDays;
            }else{
                $expDay = (int)$startDateDay + 7;
                $expMonth = $startDateMonth;
            }
        }elseif($expDate === 'monthly'){
            if($remainedDays < 30){
                $expDay = 30 - $remainedDays;
            }else{
                $expDay = $remainedDays;
                $expMonth = $startDateMonth;
            }

        }


        if(!isset($expMonth)){
            if((int)$startDateMonth === 12){
                $expMonth = 1;
                $expYear = $startDateYear + 1;
            }else{
                $expMonth = (int)$startDateMonth + 1;
                $expYear = $startDateYear;
            }
        }

        if($expDay < 9){
            $expDay = '0' . $expDay;
        }

        return ['expDay'=>$expDay, 'expMonth'=>(string)$expMonth ?? $startDateMonth, 'expYear' => $expYear ?? $startDateYear];

    }

}