<?php
namespace Classes\Result;

class GeoHelperStreet extends GeoHelperResult
{
    public $streetId;
    public $name;
    public $cityId;
    public $streetTypeCode;
    public $streetTypeName;

    public function __construct($result)
    {
        $this->streetId = $result['id'];
        $this->cityId = $result['cityId'];
        $this->name = $result['name'];
        $this->streetTypeCode = $result['localityType']['code'];
        $this->streetTypeName = $result['localityType']['name'];
    }
}