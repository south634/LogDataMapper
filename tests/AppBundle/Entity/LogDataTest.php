<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\LogData;

class LogDataTest extends \PHPUnit_Framework_TestCase
{
    protected $logData;
    protected $latitude;
    protected $longitude;
    protected $country;
    protected $state;
    protected $city;
    protected $zipCode;
    protected $browser;
    protected $deviceType;
    protected $os;

    public function setUp()
    {
        $this->logData = new LogData();
        $this->latitude = 42.5002;
        $this->longitude = -70.8652;
        $this->country = 'United States';
        $this->state = 'Massachusetts';
        $this->city = 'Marblehead';
        $this->zipCode = '01945';
        $this->browser = 'Firefox';
        $this->deviceType = 'Desktop';
        $this->os = 'Windows';
    }
    
    public function testEntityGettersAndSettersWorkProperly()
    {
        $this->assertNull($this->logData->getId());
        
        $this->logData->setLatitude($this->latitude);
        $this->assertEquals($this->latitude, $this->logData->getLatitude());
        
        $this->logData->setLongitude($this->longitude);
        $this->assertEquals($this->longitude, $this->logData->getLongitude());
        
        $this->logData->setCountry($this->country);
        $this->assertEquals($this->country, $this->logData->getCountry());
        
        $this->logData->setState($this->state);
        $this->assertEquals($this->state, $this->logData->getState());
        
        $this->logData->setCity($this->city);
        $this->assertEquals($this->city, $this->logData->getCity());
        
        $this->logData->setZipCode($this->zipCode);
        $this->assertEquals($this->zipCode, $this->logData->getZipCode());
        
        $this->logData->setBrowser($this->browser);
        $this->assertEquals($this->browser, $this->logData->getBrowser());
        
        $this->logData->setDeviceType($this->deviceType);
        $this->assertEquals($this->deviceType, $this->logData->getDeviceType());
        
        $this->logData->setOs($this->os);
        $this->assertEquals($this->os, $this->logData->getOs());
    }
}