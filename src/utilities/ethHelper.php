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
                $res = $client->request('GET', 'https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=BTC,USD,EUR');
                $res->getStatusCode();
                $res->getHeaderLine('content-type');
                $body = $res->getBody();
                $json = json_decode($body);

                if (!empty($json)) {
                    break;
                }
            }catch (Exception $error) {
                if ($i == 3) {
                    if (empty($json)){
                        // throw error on Guzzle response
                        error_log($error);
                        print("<span>Something went wrong! please refresh the page...</span>");
                        exit;
                    }
                }
            }
        }

        // throw error on api response
        if(isset($json->Response)) {
            if ($json->Response === "Error") {
                error_log($json->Message);
                print("<span>Something went wrong! please refresh the page...</span>");
                exit;
            }
        }

        return $json->USD;
    }
}