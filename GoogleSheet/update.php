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


$updateData=$_POST['formData'];
$id=$updateData[0];
$obj=new Savedata();
$rowData=$obj->fetchRow($get_range);
$combineData=array_combine($rowData,$updateData);
$sql=$obj->buildSqlUpdate($getRange,$combineData,$id);
$isUpdateDataInDb=$obj->updateDataInDb($sql);

unset($updateData[0]);
array_splice($updateData, 0, 0);
$id++;
$updateRange = "{$id}:{$id}"; 
$values = [$updateData];
$body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
    $params = ['valueInputOption' => 'RAW'];
$updateSheet = $service->spreadsheets_values->update($spreadSheetId, $updateRange, $body, $params);
header("location:list.php?msg=updateSuccess");