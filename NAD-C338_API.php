<?php

/*

https://github.com/KiboOst/php-NADC338API

*/

class NADC338{

    public $_version = '0.1';

    //USER FUNCTIONS==================================================
    public function powerOn()
    {
        $cmd = 'Main.Power=On';
        $answer = $this->sendCmd($cmd, true);
        //7sec to power on...

        sleep(8);
        $answer = explode('=', $answer)[1];
        if ($answer == 'On') $state = 1;
        else $state = 0;
        $this->_data['power'] = $state;
        return $state;
    }

    public function powerOff()
    {
        $cmd = 'Main.Power=Off';
        $answer = $this->sendCmd($cmd, true);
        $answer = explode('=', $answer)[1];
        if ($answer == 'On') $state = 1;
        else $state = 0;
        $this->_data['power'] = $state;
        return $state;
    }

    public function mute()
    {
        $cmd = 'Main.Mute=On';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function unMute()
    {
        $cmd = 'Main.Mute=Off';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function setVolume($vol=-20) //will automatically power on if off, without setting volume
    {
        if ($vol >= -80 && $vol <= 12)
        {
            if ($this->_data['power'] == 'Off') $this->powerOn();
            $cmd = 'Main.Volume='.$vol;
            $answer = $this->sendCmd($cmd, true);
            return explode('=', $answer)[1];
        }
        else return 'Volume must be between -80 and 12 by 0.5 step';
    }

    public function setSource($source=null) //will automatically power on if off, without setting source
    {
        if (in_array($source, $this->_Sources))
        {
            $s = $this->getSource();
            if ($s == $source) return $source;

            if ($this->_data['power'] == 'Off') $this->powerOn();
            $cmd = 'Main.Source='.$source;
            $answer = $this->sendCmd($cmd, true);
            return explode('=', $answer)[1];
        }
        else return 'Unavailable Source';
    }

    public function getPower()
    {
        $cmd = 'Main.Power?';
        $answer = $this->sendCmd($cmd, true);
        $answer = explode('=', $answer)[1];
        if ($answer == 'On') $state = 1;
        else $state = 0;
        return $state;
    }

    public function getVolume()
    {
        $cmd = 'Main.Volume?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function getMute()
    {
        $cmd = 'Main.Mute?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function getSource()
    {
        $cmd = 'Main.Source?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function getBrightness()
    {
        $cmd = 'Main.Brightness?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function setBrightness($level=2)
    {
        if (in_array($level, array(0,1,2,3)))
        {
            $cmd = 'Main.Brightness='.$level;
            $answer = $this->sendCmd($cmd, true);
            return explode('=', $answer)[1];
        }
        else return 'Set Brightness level of 0 1 2 or 3, 0 being off then low, normal, bright';
    }

    public function getBass()
    {
        $cmd = 'Main.Bass?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function setBass()
    {
        $cmd = 'Main.Bass=On';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function unsetBass()
    {
        $cmd = 'Main.Bass=Off';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function getAutoSense()
    {
        $cmd = 'Main.AutoSense?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function setAutoSense()
    {
        $cmd = 'Main.AutoSense=On';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function unsetAutoSense()
    {
        $cmd = 'Main.AutoSense=Off';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function getAutoStandby()
    {
        $cmd = 'Main.AutoStandby?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function setAutoStandby()
    {
        $cmd = 'Main.AutoStandby=On';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function unsetAutoStandby()
    {
        $cmd = 'Main.AutoStandby=Off';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }

    public function getVersion()
    {
        $cmd = 'Main.Version?';
        $answer = $this->sendCmd($cmd, true);
        return explode('=', $answer)[1];
    }


    //INTERNAL FUNCTIONS==================================================
    protected function sendCmd($cmd=null, $readReply=false)
    {
        socket_write($this->_socket, $cmd, strlen($cmd));
        if ($readReply)
        {
            $answer = socket_read($this->_socket, $this->_bufferSize, PHP_BINARY_READ);
            $answer = str_replace(array("\n", "\r"), '', $answer);
            return $answer;
        }
        return true;
    }

    public $error = null;
    public $_ip = null;
    public $_port = null;
    public $_data = array();
    public $_Sources = array('Stream', 'Wireless', 'TV', 'Phono', 'Coax1', 'Coax2', 'Opt1', 'Opt2');

    protected $_socket = null;
    protected $_bufferSize = 1024;

    protected function connect()
    {
        $this->_socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($this->_socket == false)
        {
            $this->error = socket_strerror(socket_last_error());
            return false;
        }

        $connect = @socket_connect($this->_socket, $this->_ip, $this->_port);
        if(!$connect)
        {
            $this->error = socket_strerror(socket_last_error());
            return false;
        }

        $this->_data['power'] = $this->getPower();


        return true;
    }

    /*Unused
        Main.Model? NADC338
        Main.AnalogGain? 0 ??
    */

    function __construct($ip=null, $port=30001)
    {
        $this->_ip = $ip;
        $this->_port = $port;

        if ($this->connect() == false)
        {
            return $this->error;
        }
    }

//NADC338 end
}

?>