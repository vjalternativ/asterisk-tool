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

    function getDialPlanGlobals()
    {
        $rx = 'dialplan show globals';
        $result = $this->execute($rx, false);
        $list = explode("\n", $result);
        array_pop($list);
        array_pop($list);

        $data = array();
        foreach ($list as $line) {
            $line = trim($line);
            $arr = explode("=", $line);

            if (isset($arr[0]) && isset($arr[1])) {
                $data[$arr[0]] = $arr[1];
            }
        }
        return $data;
    }

    function getSipRegistryStatus()
    {
        $rx = 'sip show registry';
        $result = $this->execute($rx, false);
        $list = explode("\n", $result);
        $line = $list[0];

        $headersRange = array();

        $headersRange['Host'] = array(
            strpos($line, "Host"),
            strpos($line, "dnsmgr") - strpos($line, "Host")
        );
        $headersRange['dnsmgr'] = array(
            strpos($line, "dnsmgr"),
            strpos($line, "Username") - strpos($line, "dnsmgr")
        );
        $headersRange['Username'] = array(
            strpos($line, "Username"),
            strpos($line, "Refresh") - strpos($line, "Username")
        );
        $headersRange['Refresh'] = array(
            strpos($line, "Refresh"),
            strpos($line, "State") - strpos($line, "Refresh")
        );
        $headersRange['State'] = array(
            strpos($line, "State"),
            strpos($line, "Reg.Time") - strpos($line, "State")
        );
        $headersRange['Reg.Time'] = array(
            strpos($line, "Reg.Time")
        );

        unset($list[0]);
        array_pop($list);
        $data = array();
        foreach ($list as $line) {
            $item = array();
            foreach ($headersRange as $field => $range) {

                if (isset($range[0])) {

                    if (isset($range[1])) {
                        $item[$field] = trim(substr($line, $range[0], $range[1]));
                    } else {
                        $item[$field] = trim(substr($line, $range[0]));
                    }
                }
            }
            if ($item) {
                $data[] = $item;
            }
        }
        return $data;
    }
}
?>