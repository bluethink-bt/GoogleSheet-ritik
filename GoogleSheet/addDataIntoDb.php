<?php 
require __DIR__ . '/vendor/autoload.php';
use Assets\Savedata;
//fetching data from Google Sheet
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
$column=$values[0]; //removing column names
$column=$obj->removeNull($column); //removing all null values in column
$data=$_POST['addData'];

$isDataInsert=$obj->addSingleData($getRange,$column,$data); //adding data into the database
if($isDataInsert) {  //adding data into Google Sheet
    $maxId=$obj->fetchMaxId($getRange);  //fetching the maximum/last id
    $maxId++;
    $updateRange = "{$maxId}:{$maxId}"; 
    $values = [$data];
    $body = new Google_Service_Sheets_ValueRange([
                'values' => $values
            ]);
        $params = ['valueInputOption' => 'RAW'];
    $update_sheet = $service->spreadsheets_values->update($spreadSheetId, $updateRange, $body, $params);
    header("location:list.php?msg=addSuccess");
}
