<?php
namespace Request;

class GeoHelperRequest
{
    private $method;
    private $apiKey;
    private $result;
    private $cityId;
    private $url;

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
        $this->url = "http://geohelper.info/api/v1/$method?$strParams";
        $curlHandler = curl_init();
        curl_setopt($curlHandler, CURLOPT_URL, $this->url);
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curlHandler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curlHandler, CURLOPT_FAILONERROR, false);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curlHandler, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curlHandler, CURLOPT_TIMEOUT, 30);
        curl_setopt($curlHandler, CURLOPT_CONNECTTIMEOUT, 30);
        $response = curl_exec($curlHandler);
        $statusCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
        $errno = curl_errno($curlHandler);
        $error = curl_error($curlHandler);

        curl_close($curlHandler);
        if ($errno) {
            throw new \Exception("Curl error" . $error . $errno);
        }

        $this->result = json_decode($response, true);
        if ($this->result == null) {
            throw new \Exception("JSON decoding error: " . json_last_error_msg(), 1);
        }

        return $this;
    }

    public function distributeResult()
    {
        if (false == $this->result["success"]) {
            if (array_key_exists("details", $this->result["error"])) {
                $errorMsg = "Request = " . $this->url . ", Details: " . print_r($this->result["error"]["details"], 1);
            }
            throw new \Exception("Result not success: " . $errorMsg, 2);
        }

        $geoResults = array();

        switch ($this->method) {
            case "streets":
                $geoResults = \Result\GeoHelperStreet::getObjArray($this->result);
                break;
            case "phone-data":
                $geoResults = \Result\GeoHelperPhone::getObjArray($this->result);
                break;
            default:
                throw new \Exception("Unknown type of method.", 3);
        }

        return $geoResults;
    }

    private function getNumCount($strPhone)
    {
        $phoneNums = preg_replace("/[^0-9]/", '', $strPhone);
        return strlen($phoneNums);
    }

    public function validateShortPhone($strPhone)
    {
        if ($this->getNumCount($strPhone) < 10) {
            return false;
        }
        return true;
    }

    public function validateLongPhone($strPhone)
    {
        if ($this->getNumCount($strPhone) > 15) {
            return false;
        }
        return true;
    }
}
