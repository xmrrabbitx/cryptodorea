<?php

namespace Cryptodorea\DoreaCashback\utilities;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * getting latest price of ethereum
 */
class ethHelper
{
    /**
     * @throws GuzzleException
     */
    static function ethPrice(): float
    {

        static $json;

        $client = new Client(
            [
                'verify'=>false
            ]
        );

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
                        error_log($error);
                        print("<span>Something went wrong! please refresh the page...</span>");
                        exit;
                    }
                }
            }
        }

        // throw error on api response
        if(isset($response->errors)) {
            error_log($response->errors['http_request_failed'][0]);
            print("<span>Something went wrong! please refresh the page...</span>");
            exit;
        }

        return $json->USD;
    }
}