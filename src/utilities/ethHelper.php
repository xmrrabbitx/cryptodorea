<?php

namespace Cryptodorea\DoreaCashback\utilities;

use Exception;

/**
 * getting latest price of ethereum
 */
class ethHelper
{
    /**
     * @throws Exception
     */
    static function ethPrice(): float
    {

        static $json;

        for ($i = 0; $i <= 3; $i++) {
            try {
                $response = wp_remote_get('https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=BTC,USD,EUR');
                if(!empty($response)) {
                    if(!isset($response->errors)) {
                        if (isset($response['body'])) {
                            $json = json_decode($response['body']);
                            if (!empty($json)) {
                                break;
                            }
                        }
                    }
                }
            }catch (Exception $error) {
                if ($i == 3) {
                    if (empty($response)){
                        // throw error on Guzzle response
                        print("<span>Something went wrong! please refresh the page...</span>");
                        exit;
                    }
                }
            }
        }

        // throw error on api response
        if(isset($response->errors)) {
            print("<span>Something went wrong! please refresh the page...</span>");
            exit;
        }

        return $json->USD;
    }
}