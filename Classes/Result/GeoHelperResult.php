<?php
namespace Classes\Result;

class GeoHelperResult
{
    public static function getEntity($result)
    {
        return new static($result);
    }

    public static function getObjArray($arResult)
    {
        $objArray = array();
        foreach ($arResult as $result) {
            $objArray[] = self::getEntity($result);
        }
        
        return $objArray;
    }
}