Реализован два метода:
- получение улиц по фильтру,
- получение информации по телефону.

Примеры использования.
Улицы:
```
require "autoload.php";
$geo = new \Classes\Request\GeoHelperRequest('apiKey geoHelper');

try {
    $result = $geo->makeRequest('streets', array(
        'filter[name]' => 'стах'
    ));
    $objResults = $result->distributeResult();
} catch (Exception $e) {
    echo $e->getMessage() . $e->getTraceAsString();
}

print_r($objResults);
```
Номер телефона:
```
$result = $geo->makeRequest('phone-data', array(
    'filter[phone]' => '8800908090'
));
$objResults = $result->distributeResult();
```