<?php
require __DIR__ . '/vendor/autoload.php';
use Assets\Savedata;
$get_range = "Sheet1";
$obj=new Savedata();
$rowData=$obj->fetchRow($get_range);  //fetching column name
$columnData=$obj->fetchData($get_range);  //fetching column data
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

tr:nth-child(even) {
  background-color: #f2f2f2;
}
.info {
  border-color: #2196F3;
  color: dodgerblue;
  cursor: pointer;
}
.info:hover {
  background: #2196F3;
  color: white;
}
    </style>
</head>
<body>
<button class='btn info'><a href="sync.php">Sync</a></button></a>
<button class='btn info'><a href="addForm.php">+ Add data</a></button></a>
<table class='table'>
    <?php foreach($rowData as $row){  //fetching column name
        echo "<th>".$row."</th>";
    }
    ?>
    <th>Action</th>
    <?php 
    while($column=mysqli_fetch_assoc($columnData)) { 
        echo"<tr>";
        foreach( $rowData as $ls) {  //fetching column data
            echo " <td>". $column[$ls] ."</td>"; 
        }
        echo " <td><a href='edit.php?id={$column['id']}'><button class='btn info'>Edit</button></a></td>";
    }
        echo "</tr>";
    ?>
    
</table>
</body>
</html>





