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
        $rx = 'core show channels';
        $result = $this->execute($rx, false);
        $list = explode("\n", $result);
        unset($list[0]);
        array_pop($list);
        array_pop($list);
        array_pop($list);
        return $list;
    }
}
?>