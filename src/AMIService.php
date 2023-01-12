<?php
namespace vizzy\php\lib\asterisk;

use AstMan;
$dir = __DIR__;
require_once $dir . '/libs/astman.php';

class AMIService
{

    private $astman;

    public function __construct($host, $user, $secret)
    {
        $this->astman = new AstMan();
        $this->astman->Login($host, $user, $secret);
    }

    function generateChannels($num, $entity, $context, $exten)
    {
        for ($i = 1; $i <= $num; $i ++) {
            $data = 'action=Originate,channel=SIP/' . $i . '@' . $entity . ',exten=' . $exten . ',context=' . $context . ',priority=1,async=true';
            $this->astman->request($data);
        }
    }
}
?>