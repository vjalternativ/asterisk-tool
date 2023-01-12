<?php
use vizzy\php\lib\asterisk\AMIService;

class LoadGeneratorThread extends Thread
{

    private $amiService;

    function __construct(AMIService $service)
    {
        $this->amiService = $service;
    }

    /**
     *
     * {@inheritdoc}
     * @see Threaded::run()
     */
    public function run()
    {
        echo "executing thread " . PHP_EOL;
        $this->amiService->generateChannels(10, "test", "test", "moh");
    }
}
?>