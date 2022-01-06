<?php
require_once '../src/AMIServiceProvider.php';
require_once '../src/SIPEntityGenerator.php';
use vizzy\php\lib\asterisk\AMIServiceProvider;
use vizzy\php\lib\asterisk\SIPEntityGenerator;

class AMIServiceTest
{

    function run()
    {
        $sipEntityGenerator = new SIPEntityGenerator();
        $sipEntityGenerator->createEntity("test");

        $extenVsLines = array();
        $extenVsLines['moh'][] = "Playback(moh)";
        $sipEntityGenerator->createExtension("test", $extenVsLines);

        // $sipEntityGenerator->reload();
        AMIServiceProvider::getInstance()->registerAMI("callserver", "localhost", "ameyodebug", "dacx");
        $amiService = AMIServiceProvider::getInstance()->getAMIService("callserver");
        $amiService->generateChannels(50, "test", "test", "moh");
    }
}

$ob = new AMIServiceTest();
$ob->run();
?>