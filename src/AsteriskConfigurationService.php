<?php
namespace vizzy\php\lib\asterisk;

class AsteriskConfigurationService
{

    private $filepath;

    private $binary;

    private $sipList = array();

    private $extensionList = array();

    function __construct($filepath = "/dacx/var/ameyo/dacxdata/asterisks/13/etc/asterisk/", $binary = "/dacx/ameyo/asterisks/13/sbin/asterisk")
    {
        $this->filepath = $filepath;
        $this->binary = $binary;
        $this->sipList = $this->getContextList("sip.conf");
        $this->extensionList = $this->getContextList("extensions.conf");
    }

    private function getContextList($file)
    {
        $list = array();
        $handle = fopen($this->filepath . $file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {

                $line = trim($line);
                if ($line) {

                    $line = str_replace(" ", "", $line);

                    if ($line) {

                        if (substr($line, 0, 1) == "[") {

                            $line = str_replace("[", "", $line);
                            $line = str_replace("]", "", $line);

                            $list[$line] = $line;
                        }
                    }
                }

                // process the line read.
            }

            fclose($handle);
        }

        return $list;
    }

    private function appendSIPEntity($name, $host = "localhost", $port = "8800")
    {
        $sip = PHP_EOL . '[' . $name . ']' . PHP_EOL;
        $sip .= 'type=friend' . PHP_EOL;
        $sip .= 'username=' . $name . PHP_EOL;
        $sip .= 'secret=' . $name . PHP_EOL;
        $sip .= 'host=' . $host . PHP_EOL;
        $sip .= 'port=' . $port . PHP_EOL;
        $sip .= 'dtmfmode=rfc2833' . PHP_EOL;
        $sip .= 'fromdomain=dynamic' . PHP_EOL;
        $sip .= 'nat=no' . PHP_EOL;
        $sip .= 'canreinvite=no' . PHP_EOL;
        $sip .= 'context=' . $name . PHP_EOL;
        $sip .= 'fromuser=' . $name . PHP_EOL;

        $x = file_put_contents($this->filepath . 'sip.conf', $sip, FILE_APPEND | LOCK_EX);
        if ($x) {
            echo "Entity Added " . $name . PHP_EOL;
        } else {
            echo "Got error while adding " . $name . PHP_EOL;
        }
    }

    public function reload()
    {
        $cmd = $this->binary . ' -rx "sip reload"';
        shell_exec($cmd);
        $cmd = $this->binary . ' -rx "dialplan reload"';
        shell_exec($cmd);
    }

    function createEntity($name, $host = "localhost", $port = "8800")
    {
        if (isset($this->sipList[$name])) {
            echo "Entity already exist " . $name . PHP_EOL;
        } else {

            $this->appendSIPEntity($name, $host, $port);
        }
    }

    public function createExtension($name, $extenVsLines = array())
    {
        if (isset($this->extensionList[$name])) {
            echo "Extension already exist " . $name . PHP_EOL;
        } else {

            $sip = PHP_EOL . '[' . $name . ']' . PHP_EOL;

            $sip .= 'exten => h,1,Hangup()' . PHP_EOL;
            $sip .= 'exten => t,1,Hangup()' . PHP_EOL;

            foreach ($extenVsLines as $exten => $lines) {

                $i = 1;
                foreach ($lines as $line) {

                    $sip .= 'exten => ' . $exten . ',' . $i . ',' . $line . PHP_EOL;

                    $i ++;
                }
            }

            $x = file_put_contents($this->filepath . 'extensions.conf', $sip, FILE_APPEND | LOCK_EX);
            if ($x) {
                echo "Extension Added " . $name . PHP_EOL;
            } else {
                echo "Got error while adding " . $name . PHP_EOL;
            }
        }
    }
}

?>