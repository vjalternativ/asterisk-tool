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

    function getRegistrationStatus()
    {
        $rows = $this->asteriskCLI->getSipRegistryStatus();

        foreach ($rows as $key => $row) {

            $row['status'] = "Down";

            if ($row['Reg.Time']) {
                $date = DateTime::createFromFormat("D, d M Y H:i:s", $row['Reg.Time']);
                $row['regtime'] = $date->format("Y-m-d H:i:s");
            }

            if ($row['State'] == 'Registered') {
                $row['status'] = "Up";
            } else {

                if ($row['Reg.Time']) {
                    $now = new DateTime();
                    $now->sub(new DateInterval("PT" . $row['Refresh'] . "S"));
                    $nowMinusRefresh = $now->format("Y-m-d H:i:s");
                    if ($nowMinusRefresh <= $row['regtime']) {
                        $row['status'] = "Up";
                    }
                }
            }

            $rows[$key] = $row;
        }

        return $rows;
    }

    function execute()
    {
        $list = $this->asteriskCLI->getSIPShowPeers();
        echo "<pre>";
        print_r($list);
        die();
    }
}

$ob = new AsteriskToolTester();
$ob->execute();
?>