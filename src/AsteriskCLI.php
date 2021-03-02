<?php
namespace vizzy\php\lib\asterisk;

use Exception;

class AsteriskCLI
{

    private $binaryFilePath;

    function __construct($binaryFilePath)
    {
        if (file_exists($binaryFilePath)) {

            $this->binaryFilePath = $binaryFilePath;
        } else {

            throw new Exception("binary file path not exist");
        }
    }

    function execute($rx, $aftercmd)
    {
        $cmd = $this->binaryFilePath . ' -rx "' . $rx . '"';
        if ($aftercmd) {
            $cmd .= '|' . $aftercmd;
        }
        return trim(shell_exec($cmd));
    }

    function getChannels()
    {
        $rx = 'core show channels concise';
        $result = $this->execute($rx, false);
        $list = explode("\n", $result);

        $data = array();
        foreach ($list as $line) {
            $arr = explode("!", $line);
            $data[$arr[0]] = $arr;
        }

        return $data;
    }
}
?>