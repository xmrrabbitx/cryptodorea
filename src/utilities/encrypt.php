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
    private string $lastNonce;

    /**
     * create sha256 hash for secret hash on smart contract deployment
     * @return string
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
     * @throws RandomException
     * @throws Exception
     */
    public function currentNonce(){
        $_r = random_bytes(16);
        $_s = random_bytes(16);
        $_v = random_bytes(16);
        $_d = random_bytes(16);
        print_r([
            bin2hex($_r),
            bin2hex($_s),
            bin2hex($_v),
            bin2hex($_d)
        ]);
        $this->lastNonce = Keccak::hash($_r . $_s . $_v . $_d, 256);
        return $this->lastNonce;
    }

    /**
     * @throws Exception
     */
    public function nextNonce(){
        if ($this->lastNonce) {
            // Mix the last nonce with some randomness to generate the next one
            $_extra = random_bytes(16); // Add some randomness
            return Keccak::hash($this->lastNonce . $_extra, 256);
        }
        return null; // If there's no current nonce, return null
    }

    /**
     * create key-value for encryption
     * @throws Exception
     */
    public function encryptGenerate(string $campaignName):array
    {
        var_dump($this->currentNonce());
        //$options = ['currentNonce'=>$this->currentNonce(), ]
        return get_option("currentNonce_" . $campaignName) ?  update_option("currentNonce_" . $campaignName, $options) : add_option("currentNonce_" . $campaignName, $options);

    }

    /**
     * Keccake sha-3 encryption
     * paramewters must be binary!
     * @throws Exception
     */
    public function keccak($key, $value, $amount = null):string
    {
        return '0x' . Keccak::hash($key . $value . $amount, 256);
    }
}