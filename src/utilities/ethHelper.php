<?php

namespace Cryptodorea\DoreaCashback\utilities;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ethHelper
{
    /**
     * @throws GuzzleException
     */
    static function ethPrice(): float
    {
        $client = new Client([
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
            }catch (SiteErrorException $error) {
                if ($i == 3) {
                    if (empty($json)){
                        throw new SiteErrorException("no response!");
                    }
                }
            }
        }

        return $json->USD;
    }
}

