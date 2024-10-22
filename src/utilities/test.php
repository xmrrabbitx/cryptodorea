<?php

use kornrunner\Keccak;

require "vendor/autoload.php";

class sol
{


    private $current;



    public function generate($nonce=null):string
    {

        print_r([
            bin2hex($this->secret),
            bin2hex($this->salt),
        ]);
        if($nonce) {

            $this->saltValue = Keccak::hash($this->secret . $this->salt . $this->salty, 256);
            $this->v = hex2bin($this->saltValue) . $this->s;
//var_dump("_v value:".bin2hex($this->v));
            // set again
            $this->secret = random_bytes(16);
            $this->salt = random_bytes(16);
            $this->salty = random_bytes(16);

            $this->current = $this->saltValue;
            return $this->current;
        }

        $this->secret = random_bytes(16);
        $this->salt = random_bytes(16);
        $this->salty = random_bytes(16);

        $this->r = random_bytes(16);
        $this->s = random_bytes(16);

        $this->saltValue = Keccak::hash($this->secret . $this->salt . $this->salty, 256);
        $this->v = hex2bin($this->saltValue) . $this->s;
        var_dump("_v value:".bin2hex($this->v));
        $this->current = Keccak::hash($this->r . $this->s . $this->v, 256);
        return $this->current;

    }


    /**
     * @throws Exception
     */
    public function contract($r, $s, $v, $next):void
    {
        $nonce = Keccak::hash($r . $s . $v, 256);
        $nextNonce = Keccak::hash(hex2bin($next) . $s, 256);

        if($nonce === $this->current){
            var_dump("valid nonce!!!");
        }else{
            var_dump("invalid nonce!!!");
        }
        if($nextNonce === Keccak::hash($v, 256)){
            var_dump("valid next + v!!!");
            $this->current = $next;
        }else{
            var_dump("invalid next + v!!!");
        }

    }
    public function test()
    {

        $nonce = $this->generate();
        var_dump("nonce: " . $nonce);

        $next = $this->generate($nonce);
        var_dump("nonce: " . $next);

        $decrypt = bin2hex(hex2bin($next) . $this->s);
        if($decrypt === bin2hex($this->v)){
            var_dump("equal!!!");
        }

        $next = $this->generate($nonce);
        var_dump("nonce: " . $next);

        $decrypt = bin2hex(hex2bin($next) . $this->s);
        if($decrypt === bin2hex($this->v)){
            var_dump("equal!!!");
        }
        print_r("\n");


    }

}

$sol = new sol();
$sol->test();