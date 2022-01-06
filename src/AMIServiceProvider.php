<?php
namespace vizzy\php\lib\asterisk;

$dir = __DIR__;
require_once $dir . '/AMIService.php';

class AMIServiceProvider
{

    private static $instance = null;

    private $connections = array();

    private function __construct()
    {}

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new AMIServiceProvider();
        }
        return self::$instance;
    }

    public function registerAMI($profile, $host, $user, $secret)
    {
        $this->connections[$profile] = new AMIService($host, $user, $secret);
    }

    public function getAMIService($profile)
    {
        if (isset($this->connections[$profile])) {
            $ob = $this->connections[$profile];
            return $this->asAMIService($ob);
        }

        throw new \Exception("AMI Profile " . $profile . " is not registered");
    }

    private function asAMIService(AMIService $ob)
    {
        return $ob;
    }
}
?>