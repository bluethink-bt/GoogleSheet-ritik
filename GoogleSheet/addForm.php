<?php
require __DIR__ . '/vendor/autoload.php';
use Assets\Savedata;
$getRange = "Sheet1";
$obj=new Savedata();
$rowData=$obj->fetchRow($getRange);
unset($rowData[0]); //removing 'id' column
array_splice($rowData, 0, 0); //indexing starts from 0
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    table {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
        border: 1px solid #ddd;
    }

    th, td {
         text-align: left;
        padding: 16px;
    }
    input{
        width:76px
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
}
    </style>
</head>
<body>
<table class='table'>
    <?php foreach($rowData as $row){  //fetching column 
        echo "<th>".$row."</th>";
    }
    ?>
    <th>Action</th>
    <?php 
    echo"<tr>";
    foreach( $rowData as $ls) {  //fetching column data
        echo " <td> <form action='addDataIntoDb.php' method='POST'> <input type='text' required name=addData[]></td>";
    }
    echo " <td><input type='Submit' name='submit'></td>";
    echo "</tr>";
    echo "</form>";
    ?>
</table>
</body>
</html>