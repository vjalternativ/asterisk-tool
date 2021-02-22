<?php

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
}
?>