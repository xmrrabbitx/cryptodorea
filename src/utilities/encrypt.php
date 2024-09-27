<?php

namespace Cryptodorea\Woocryptodorea\utilities;

use Cryptodorea\Woocryptodorea\abstracts\utilities\encryptAbstract;
use Exception;
use kornrunner\Keccak;

/**
 * encrypt data
 */
class encrypt extends encryptAbstract
{
    /**
     * create sha256 hash for secret hash on smart contract deployment
     * @param $data
     * @return string
     * @throws \Random\RandomException
     */
    public function randomSha256()
    {

        // random bytes for random secret
        $randomSecret = base64_encode(random_bytes(64));

        $secretHash = hash('sha256',$randomSecret);

        return $secretHash;

    }


    public function sha256Salt()
    {
        // random bytes for random value
        $randomValue = base64_encode(random_bytes(64));

        // random bytes for random salt
        $randomSalt = base64_encode(random_bytes(64));

        return hash('sha256',base64_encode($randomValue . $randomSalt));
    }


    /**
     * encrypt $data using AES-PKCE encryption algorithm
     * @param $data
     * @return string
     * @throws \Random\RandomException
     */
    public function encryptAes($data)
    {

        // iv cipher must be 16 length
        $ivlen = openssl_cipher_iv_length("AES-256-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);

        $key = base64_encode(random_bytes(64));

        // set argument openssl_encrypt($options=0) to use PKCS7 padding and base64 encoding
        $encryptedData = openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($encryptedData);

    }


    /**
     * @param $data
     * @return false|string
     */
    public function decryptAes($data, $key, $iv)
    {
       return openssl_decrypt(base64_decode($data), 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    }

    /**
     * create key-value for encryption
     */
    public function encryptGenerate():array
    {
        $key = random_bytes(16);
        $value = random_bytes(16);

        return ['key'=> $key, 'value'=> $value];
    }

    /**
     * Keccake sha-3 encryption
     * paramewters must be binary!
     * @throws Exception
     */
    public function keccak($key, $value):string
    {
        return '0x' . Keccak::hash($key . $value, 256);
    }
}