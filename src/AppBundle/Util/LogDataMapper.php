<?php

namespace AppBundle\Util;

use Jenssegers\Agent\Agent;
use GeoIp2\Database\Reader;

class LogDataMapper
{

    /**
     * Used for getting device data from user agent string
     * 
     * @var Agent 
     */
    private $agent;

    /**
     * Used for reading ip geolocation data from MaxMind GeoIp database
     * 
     * @var Reader
     */
    private $geoip;

    /**
     * Stores geolocation data on previously looked up ip addresses
     * 
     * Prevents the need to re-search duplicate ip addresses in GeoIp database
     * 
     * @var array 
     */
    private $ipTable;

    /**
     * Stores device data on previously looked up user agents
     * 
     * Prevents the need to re-search duplicate user agents' data using Agent
     * 
     * @var array 
     */
    private $userAgentTable;

    /**
     * @param Agent $agent
     * @param Reader $geoip
     */
    public function __construct(Agent $agent, Reader $geoip)
    {
        $this->agent = $agent;
        $this->geoip = $geoip;
        $this->ipTable = [];
        $this->userAgentTable = [];
    }

    /**
     * Looks up IP address in MaxMind GeoIP database and returns results as array
     * 
     * Retrieves geolocation data for IP address from MaxMind GeoIP database 
     * and returns associative array of values. Any value in array which was 
     * not found in database will be returned as null.
     * 
     * @param string $ip
     * @return array
     */
    public function getGeoIpData($ip)
    {
        $record = $this->geoip->city($ip);
        
        // set empty values to null
        $latitude = $record->location->latitude ? $record->location->latitude : null;
        $longitude = $record->location->longitude ? $record->location->longitude : null;
        $country = $record->country->name ? $record->country->name : null;
        $state = $record->mostSpecificSubdivision->name ? $record->mostSpecificSubdivision->name : null;
        $city = $record->city->name ? $record->city->name : null;
        $zipCode = $record->postal->code ? $record->postal->code : null;

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'country' => $country,
            'state' => $state,
            'city' => $city,
            'zipCode' => $zipCode,
        ];
    }    

    /**
     * Looks up user agent device data and returns results as array
     * 
     * Retrieves device data for user agent using Agent and returns associative 
     * array of values. Any value in array which was not found will be returned 
     * as null. If null user agent passed as method parameter, then device type
     * will be classified as 'Robot'.
     * 
     * @param string $userAgent
     * @return array
     */
    public function getUserAgentData($userAgent)
    {
        $this->agent->setUserAgent($userAgent);

        $browser = $this->agent->browser() ? $this->agent->browser() : null;
        $os = $this->agent->platform() ? $this->agent->platform() : null;

        // get device type
        $deviceType = null;

        // set log entries with no user agent as bots
        if ($this->agent->isRobot() || $userAgent === null) {
            $deviceType = 'Robot';
        }
        elseif ($this->agent->isTablet()) {
            $deviceType = 'Tablet';
        }
        elseif ($this->agent->isMobile()) {
            $deviceType = 'Mobile';
        }
        elseif ($this->agent->isDesktop()) {
            $deviceType = 'Desktop';
        }

        return [
            'browser' => $browser,
            'deviceType' => $deviceType,
            'os' => $os,
        ];
    }

    /**
     * Searches for geoip data in array first before searching MaxMind database
     * 
     * Used for saving time on searching previously looked up ips in large 
     * MaxMind GeoIp database again. If found in ipTable array, it will pull 
     * data from there first. Otherwise will get data from MaxMind GeoIp 
     * database, and store it to ipTable array. Returns associative array of 
     * geolocation values.
     * 
     * @param string $ip
     * @return array
     */
    public function mapIpToGeoData($ip)
    {
        // Use GeoIp2 lookup only if ip geo data not present in table
        if (!isset($this->ipTable[$ip])) {
            $this->ipTable[$ip] = $this->getGeoIpData($ip);
        }
        
        return $this->ipTable[$ip];
    }

    /**
     * Searches for user agent device data in array first before using Agent
     * 
     * Used for saving time on searching previously looked up user agents 
     * with Agent. If found in userAgentTable array, it will pull data from 
     * there first. Otherwise will search device data with Agent, and store it 
     * to userAgentTable array. Returns associative array of device data values.
     * 
     * @param string $userAgent
     * @return array
     */
    public function mapUserAgentToDeviceData($userAgent)
    {
        // Use Agent lookup only if user agent device data not present in table
        if (!isset($this->userAgentTable[$userAgent])) {
            $this->userAgentTable[$userAgent] = $this->getUserAgentData($userAgent);
        }
        
        return $this->userAgentTable[$userAgent];
    }

}
