
<img align="right" src="/assets/NAD_logo.jpg" width="100">
# php-NADC338

## php API for NAD C338 Amplifier automation

Here is an API to use/integrate your NAD C338 Amplifier via php. With this API, you can power it on, set it to standby, get used source and change it, same for volume, Bass EQ, etc.

I developed this API specifically to integrate my C338 into my Jeedom. It works perfectly with a Script, both in iOS app and HomeKit. Of course you can use it with lot of automation solutions or alone with any Apache server.

<img align="right" src="/assets/C338.jpg" width="150">

## Requirements
- NAD C338 Amplifier (yes, really!). Your amplifier must be setup on your wifi network.
- PHP v5+

## How-to
- Download NAD-C338_API.php and put it on your server.
- Include NAD-C338_API.php in your php script.
- Start it with your C338 IP.

> It is better to connect to your amplifier with a web browser first, to set it to static IP.

#### Connection

```php
require('NAD-C338_API.php"); //NAD custom API
$ip = '192.168.0.38';

$_C338 = new NADC338($ip);
if (isset($_C338->error)) die('__ERROR__: '.$_C338->error);
```

Let the fun begin:

#### OPERATIONS<br />

```php
//Power:
/*
return 0 for off, 1 for on
I have set it like that for easier use in smarthome solutions, as switch are often binary.
*/
$getPower = $_C338->getPower();
echo 'getPower: ', $getPower, "<br>";

$_C338->powerOn();
$_C338->powerOff();

//Volume level:
$getVolume = $_C338->getVolume();
echo 'getVolume: ', $getVolume, "<br>";

$_C338->setVolume(); //Volume goes from -80 to 12

//Mute state:
$getMute = $_C338->getMute();
echo 'getMute: ', $getMute, "<br>";

$_C338->mute();
$_C338->unMute();

//Source:
$getSource = $_C338->getSource();
echo 'getSource: ', $getSource, "<br>";

//Available sources: 'Stream', 'Wireless', 'TV', 'Phono', 'Coax1', 'Coax2', 'Opt1', 'Opt2'
$_C338->setSource('Stream');

//LCD Brightness:
$getBrightness = $_C338->getBrightness();
echo 'getBrightness: ', $getBrightness, "<br>";

//Available value: 0 1 2 3, 0 being off then low, normal, bright
$_C338->setBrightness(2);

//Bass EQ:
$getBass = $_C338->getBass();
echo 'getBass: ', $getBass, "<br>";

$_C338->setBass();
$_C338->unsetBass();

//AutoSense:
$getAutoSense = $_C338->getAutoSense();
echo 'getAutoSense: ', $getAutoSense, "<br>";

$_C338->setAutoSense();
$_C338->unsetAutoSense();

//Auto Standby
$getAutoStandby = $_C338->getAutoStandby();
echo 'getAutoStandby: ', $getAutoStandby, "<br>";

$_C338->setAutoStandby();
$_C338->unsetAutoStandby();

//Firmware Version:
$getVersion = $_C338->getVersion();
echo 'getVersion: ', $getVersion, "<br>";

```

## Version history

#### v0.1 (2018-02-10)
- First public version!

## License

The MIT License (MIT)

Copyright (c) 2017 KiboOst

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
