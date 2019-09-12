<?php
namespace Result;

class GeoHelperPhone extends GeoHelperResult
{
    public $providerName;
    public $regionName;
    public $regionCode;
    public $regionTypeName;

    public $numCountryCode;
    public $numCode;
    public $numNumber;

    public function __construct($result)
    {
        @$this->providerName = $result['providerName'];
        @$this->regionName = $result['region']['name'];
        @$this->regionCode = $result['region']['localityType']['code'];
        @$this->regionTypeName = $result['region']['localityType']['localizedNames']['ru'];
        
        $this->numCountryCode = $result['phoneParts']['countryCode'];
        $this->numCode = $result['phoneParts']['code'];
        $this->numNumber = $result['phoneParts']['number'];
    }
}