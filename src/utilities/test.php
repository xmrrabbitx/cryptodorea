<?php

use kornrunner\Keccak;

require "vendor/autoload.php";

class sol
{


    private $current;



    public function generate($next=null):string
    {
        $this->r = random_bytes(16);
        $this->s =  random_bytes(16);

        if($next) {
            var_dump("next + s");
            $this->v = $next . $this->s;
        }else{
            var_dump("r + s");
            $this->v = $this->r . $this->s;
        }

        print_r([
            bin2hex($this->r),
            bin2hex($this->s),
            bin2hex($this->v),
        ]);

        $this->current = Keccak::hash($this->r . $this->s . $this->v, 256);
        return $this->current;

    }

    public function next()
    {
        $r = random_bytes(16);
        $s =  random_bytes(16);
        $v =  random_bytes(16);

        $this->next = Keccak::hash($r . $s . $v, 256);
        return $this->next;
    }

    /**
     * @throws Exception
     */
    public function contract($r, $s, $v, $next):void
    {
        $nonce = Keccak::hash($r . $s . $v, 256);
        $nextNonce = Keccak::hash($next . $s, 256);

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
        $next = $this->next();
        //var_dump("next nonce created: " . $next);
        $enc = $this->generate($next);
        var_dump("nonce: " . $enc);
        $this->contract($this->r, $this->s, $this->v, $next);
        var_dump("current Nonce: " . $this->current);

        print_r("\n");

        var_dump($next);
        var_dump(bin2hex($this->s));
        var_dump(Keccak::hash($next . $this->s, 256));
        var_dump(Keccak::hash($this->v, 256));
        /*
        $next2 = $this->next();
        $this->generate($next2);
        //var_dump("next nonce created: " . $next2);
        $this->contract($this->r, $this->s, $this->v, $next2);
        var_dump("current Nonce: " . $this->current);

        print_r("\n");

        $next3 = $this->next();
        $this->generate($next3);
        //var_dump("next nonce created: " . $next3);
        $this->contract($this->r, $this->s, $this->v, $next3);
        var_dump("current Nonce: " . $this->current);
*/

    }

}

$sol = new sol();
$sol->test();