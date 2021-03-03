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
        $result = trim(shell_exec($cmd));
        return explode("\n", $result);
    }

    function getChannels()
    {
        $rx = 'core show channels concise';
        $list = $this->execute($rx, false);

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
        $list = $this->execute($rx, false);
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

    function mapCLIOutputWithHeaders($headers = array(), $linesArray = array())
    {
        $line = $linesArray[0];
        $headersRange = array();
        foreach ($headers as $key => $header) {

            if (isset($headers[$key + 1])) {

                $nextHeader = $headers[$key + 1];

                $headersRange[$header] = array(
                    strpos($line, $header),
                    strpos($line, $nextHeader) - strpos($line, $header)
                );
            } else {
                $headersRange[$header] = array(
                    strpos($line, $header)
                );
            }
        }

        unset($linesArray[0]);
        $data = array();
        foreach ($linesArray as $line) {
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

    function getSipShowRegistry()
    {
        $rx = 'sip show registry';
        $list = $this->execute($rx, false);
        array_pop($list);

        $headers = array(
            "Host",
            "dnsmgr",
            "Username",
            "Refresh",
            "State",
            "Reg.Time"
        );

        return $this->mapCLIOutputWithHeaders($headers, $list);
    }

    function getSIPShowPeers()
    {
        $rx = 'sip show peers';
        $list = $this->execute($rx, false);

        array_pop($list);

        $headers = array(
            "Name/username",
            "Host",
            "Dyn",
            "Forcerport",
            "Comedia",
            "ACL Port",
            "Status",
            "Description"
        );

        return $this->mapCLIOutputWithHeaders($headers, $list);
    }
}
?>