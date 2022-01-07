<?php
use vizzy\php\lib\asterisk\AsteriskConfigurationServiceProvider;

class AsteriskConfigurationServiceTest
{

    function execute()
    {
        AsteriskConfigurationServiceProvider::getInstance()->registerConfigurationService("callserver", "/tmp", "/test");
        $ob = AsteriskConfigurationServiceProvider::getInstance()->getConfigurationService("callserver");
        $ob->createEntity("test");
    }
}

$ob = new AsteriskConfigurationServiceTest();
$ob->execute();
?>