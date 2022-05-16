<?php
require __DIR__ . '/vendor/autoload.php';

use Assets\Savedata ;

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
$obj=new Savedata();

$column=$values[0];
$column=$obj->removeNull($column); //removing null column

unset($values[0]);
$values=$obj->removeNull($values); //removing null data values
if($obj->isTableExist($getRange)) {  //cehcking if table exists
    if($obj->deleteTable($getRange)) {  //deleting table
        if($obj->createTable($getRange,$column)) {  //creating table
            if($obj->insertData($getRange,$column,$values)){  //inserting table
                header("location:list.php");  //redirecting to list.php
            }else {
                header("location:sheets.php?msg=insertionFail");  
            }
        }else{
            header("location:sheets.php?msg=tableCreationFail");
        }
    } else {
        header("location:sheets.php?msg=deletionFail");
    }
} else {
    header("location:sheets.php?msg=tableNotFound");
}
