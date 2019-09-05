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
        if (array_key_exists("pagination", $arResult)) {
            foreach ($arResult['result'] as $result) {
                $objArray[] = self::getEntity($result);
            }
        } else {
            $objArray[] = self::getEntity($arResult['result']);
        }

        return $objArray;
    }
}