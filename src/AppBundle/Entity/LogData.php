<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class LogData
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="decimal", precision=9, scale=7, nullable=true, options={"default":null})
     */
    protected $latitude;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=7, nullable=true, options={"default":null})
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true, options={"default":null})
     */
    protected $country;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true, options={"default":null})
     */
    protected $state;
    
    /**
     * @ORM\Column(type="string", length=85, nullable=true, options={"default":null})
     */
    protected $city;
    
    /**
     * @ORM\Column(type="string", length=16, nullable=true, options={"default":null})
     */
    protected $zipCode;
    
    /**
     * @ORM\Column(type="string", length=30, nullable=true, options={"default":null})
     */
    protected $browser;
    
    /**
     * @ORM\Column(type="string", length=7, nullable=true, options={"default":null})
     */
    protected $deviceType;
    
    /**
     * @ORM\Column(type="string", length=50, nullable=true, options={"default":null})
     */
    protected $os;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return LogData
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return LogData
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return LogData
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return LogData
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return LogData
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set zipCode
     *
     * @param string $zipCode
     *
     * @return LogData
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * Get zipCode
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * Set browser
     *
     * @param string $browser
     *
     * @return LogData
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;

        return $this;
    }

    /**
     * Get browser
     *
     * @return string
     */
    public function getBrowser()
    {
        return $this->browser;
    }

    /**
     * Set deviceType
     *
     * @param string $deviceType
     *
     * @return LogData
     */
    public function setDeviceType($deviceType)
    {
        $this->deviceType = $deviceType;

        return $this;
    }

    /**
     * Get deviceType
     *
     * @return string
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * Set os
     *
     * @param string $os
     *
     * @return LogData
     */
    public function setOs($os)
    {
        $this->os = $os;

        return $this;
    }

    /**
     * Get os
     *
     * @return string
     */
    public function getOs()
    {
        return $this->os;
    }
}
