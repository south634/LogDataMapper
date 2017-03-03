<?php

namespace Tests\AppBundle\Util;

use AppBundle\Util\LogDataMapper;

class LogDataMapperTest extends \PHPUnit_Framework_TestCase
{

    protected $agent;
    protected $geoip;
    protected $geoDataObject;

    protected function setUp()
    {
        $this->agent = $this->getMockBuilder('\Jenssegers\Agent\Agent')
                ->setMethods(null)
                ->getMock();
        
        $this->geoip = $this->getMockBuilder('\GeoIp2\Database\Reader')
                ->setMethods(array('__construct', 'city'))
                ->setConstructorArgs(array('pathToDatabaseFile'))
                ->disableOriginalConstructor()
                ->getMock();

        // create mock geodata object returned by $geoip->city() method
        $location = new \stdClass();
        $location->latitude = 42.5002;
        $location->longitude = -70.8652;
        
        $country = new \stdClass();
        $country->name = 'United States';
        
        $mostSpecificSubdivision = new \stdClass();
        $mostSpecificSubdivision->name = 'Massachusetts';
        
        $city = new \stdClass();
        $city->name = 'Marblehead';
        
        $postal = new \stdClass();
        $postal->code = '01945';
        
        $this->geoDataObject = new \stdClass();
        $this->geoDataObject->location = $location;
        $this->geoDataObject->country = $country;
        $this->geoDataObject->mostSpecificSubdivision = $mostSpecificSubdivision;
        $this->geoDataObject->city = $city;
        $this->geoDataObject->postal = $postal;
        
    }

    public function testGetGeoIpDataReturnsExpectedArray()
    {
        $logDataMapper = new LogDataMapper($this->agent, $this->geoip);

        $expected = [
            'latitude' => 42.5002,
            'longitude' => -70.8652,
            'country' => 'United States',
            'state' => 'Massachusetts',
            'city' => 'Marblehead',
            'zipCode' => '01945',
        ];
        
        $this->geoip->expects($this->once())
                ->method('city')
                ->will($this->returnValue($this->geoDataObject));           

        $result = $logDataMapper->getGeoIpData('98.217.63.220');

        $this->assertEquals($expected, $result);
    }

    public function testMapIpToGeoDataReturnsExpectedArray()
    {
        $logDataMapper = new LogDataMapper($this->agent, $this->geoip);

        $expected = [
            'latitude' => 42.5002,
            'longitude' => -70.8652,
            'country' => 'United States',
            'state' => 'Massachusetts',
            'city' => 'Marblehead',
            'zipCode' => '01945',
        ];
        
        $this->geoip->expects($this->once())
                ->method('city')
                ->will($this->returnValue($this->geoDataObject));            

        $result = $logDataMapper->mapIpToGeoData('98.217.63.220');

        $this->assertEquals($expected, $result);
    }

    public function testGetUserAgentDataReturnsExpectedArrayOnNullUserAgent()
    {
        $logDataMapper = new LogDataMapper($this->agent, $this->geoip);

        $expected = [
            'browser' => null,
            'deviceType' => 'Robot',
            'os' => null,
        ];

        $result = $logDataMapper->getUserAgentData(null);

        $this->assertEquals($expected, $result);
    }

    public function testGetUserAgentDataReturnsExpectedArrayForUserAgent()
    {
        $logDataMapper = new LogDataMapper($this->agent, $this->geoip);

        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0';

        $expected = [
            'browser' => 'Firefox',
            'deviceType' => 'Desktop',
            'os' => 'Windows',
        ];

        $result = $logDataMapper->getUserAgentData($userAgent);

        $this->assertEquals($expected, $result);
    }

    public function testMapUserAgentToDeviceDataReturnsExpectedArrayOnNullUserAgent()
    {
        $logDataMapper = new LogDataMapper($this->agent, $this->geoip);

        $expected = [
            'browser' => null,
            'deviceType' => 'Robot',
            'os' => null,
        ];

        $result = $logDataMapper->mapUserAgentToDeviceData(null);

        $this->assertEquals($expected, $result);
    }

    public function testMapUserAgentToDeviceDataReturnsExpectedArrayForUserAgent()
    {
        $logDataMapper = new LogDataMapper($this->agent, $this->geoip);

        $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:51.0) Gecko/20100101 Firefox/51.0';

        $expected = [
            'browser' => 'Firefox',
            'deviceType' => 'Desktop',
            'os' => 'Windows',
        ];

        $result = $logDataMapper->mapUserAgentToDeviceData($userAgent);

        $this->assertEquals($expected, $result);
    }

}
