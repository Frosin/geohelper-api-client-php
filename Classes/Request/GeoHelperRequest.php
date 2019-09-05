<?php
namespace Classes\Request;

class GeoHelperRequest
{
    private $method;
    private $apiKey;
    private $result;
    private $cityId;

    public function __construct($apiKey, $cityId = 3299)
    {
        $this->apiKey = $apiKey;
        $this->cityId = $cityId;
    }

    public function makeRequest($method, $params)
    {
        $this->method = $method;
        $params['apiKey'] = $this->apiKey;
        $params['filter[cityId]'] = $this->cityId;
        $params['locale[lang]'] = 'ru';
        $strParams = http_build_query($params);
        $response = file_get_contents("http://geohelper.info/api/v1/$method?$strParams");
        $this->result = json_decode($response, true);
        if ($this->result == null) {
            throw new Exception("JSON decoding error: " . json_last_error_msg(), 1);
        }

        return $this;
    }

    public function distributeResult()
    {
        if (false == $this->result["success"]) {
            $errorMsg =  $this->result["error"];
            if (array_key_exists("details", $this->result["error"])) {
                $errorMsg .= ", details: " . print_r($this->result["error"]["details"], 1);
            }
            throw new Exception("Result not success: " . $errorMsg, 2);
        }

        $geoResults = array();

        switch ($this->method) {
            case "streets":
                $geoResults = \Classes\Result\GeoHelperStreet::getObjArray($this->result['result']);
                break;
            case "phone-data":
                $geoResults = \Classes\Result\GeoHelperPhone::getObjArray($this->result['result']);
                break;
            default:
                throw new Exception("Unknown type of method.", 3);
        }

        return $geoResults;
    }
}