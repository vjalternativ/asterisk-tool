<?php
require_once '../src/AsteriskCLI.php';

use vizzy\php\lib\asterisk\AsteriskCLI;
date_default_timezone_set("Asia/Kolkata");

class AsteriskToolTester
{

    public $asteriskCLI;

    function __construct()
    {
        $this->asteriskCLI = new AsteriskCLI('/dacx/ameyo/asap/sbin/asterisk');
    }

    function execute()
    {
        $list = $this->getRegistrationStatus();
        echo "<pre>";
        print_r($list);
        die();
    }
}

$ob = new AsteriskToolTester();
$ob->execute();
?>