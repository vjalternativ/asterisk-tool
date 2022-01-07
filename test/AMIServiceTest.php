<?php
require_once '../src/AMIServiceProvider.php';
require_once '../src/AsteriskConfigurationService.php';
use vizzy\php\lib\asterisk\AMIServiceProvider;
use vizzy\php\lib\asterisk\AsteriskConfigurationService;

class AMIServiceTest
{

    function run()
    {
        $sipEntityGenerator = new AsteriskConfigurationService();
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