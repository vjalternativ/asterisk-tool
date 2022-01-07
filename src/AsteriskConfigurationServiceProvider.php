<?php
namespace vizzy\php\lib\asterisk;

class AsteriskConfigurationServiceProvider
{

    private static $instance = null;

    private $profileVsConfigurationService = array();

    private function __construct()
    {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new AsteriskConfigurationServiceProvider();
        }
        return self::$instance;
    }

    public function registerConfigurationService($profile, $confpath, $binarypath)
    {
        $this->profileVsConfigurationService[$profile] = new AsteriskConfigurationService($confpath, $binarypath);
    }

    public function getConfigurationService($profile)
    {
        return isset($this->profileVsConfigurationService[$profile]) ? $this->profileVsConfigurationService[$profile] : false;
    }

    private function asConfigurationService(AsteriskConfigurationService $ob)
    {
        return $ob;
    }
}
?>