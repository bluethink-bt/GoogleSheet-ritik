<?php
require __DIR__ . '/vendor/autoload.php';
use Assets\Savedata;
$getRange = "Sheet1";
$obj=new Savedata();
$rowData=$obj->fetchRow($getRange); //fetching header fields
$id=$_GET['id'];
$columnData=$obj->fetchById($id,$getRange); //fetching data by id
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
    <?php foreach($rowData as $row){   //displaying all columns
        echo "<th>".$row."</th>";
    }
    ?>
    <th>Action</th>
    <?php 
    echo"<tr>";
    foreach( $rowData as $ls) {  //displaying data of all columns
        echo " <td> <form action='update.php?id={$columnData[0]['id']}' method='Post'> <input type='text' name=formData[] value =".$columnData[0][$ls]."></td>";
    }
    echo " <td><input type='Submit' name='submit'></td>";
    echo "</tr>";
    echo "</form>";
    ?>
</table>
</body>
</html>
