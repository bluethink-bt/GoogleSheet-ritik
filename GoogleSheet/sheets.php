<?php

require __DIR__ . '/vendor/autoload.php';

use Assets\Savedata;

$client = new \Google_Client();
$client->setApplicationName('Google Sheets and PHP');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$service = new Google_Service_Sheets($client);
$spreadSheetId = "1Er9segOOOW7-zvg0yLItWDvIZOQY7i1aCG7U8OBLdIA"; //It is present in your URL
$getRange = "Sheet1";
$response = $service->spreadsheets_values->get($spreadSheetId, $getRange);
$values = $response->getValues();

// $column=isset($values[0])?$values[0]:'1';
$obj= new Savedata(); // object of common functions
$column=$values[0];
$column=$obj->removeNull($column);


unset($values[0]); // remove header of excel sheet
$values=$obj->removeNull($values);
if(!$obj->isTableExist($getRange)) {
   if($obj->createTable($getRange,$column)) {
        if($obj->insertData($getRange,$column,$values)) {
            header("location:list.php");
        } else {
            echo "Something went wrong.";
        }     
    } else {
        echo "Column name must be unique and not null";
    }
} else {
    header("location:list.php?msg=tableExist");
}



