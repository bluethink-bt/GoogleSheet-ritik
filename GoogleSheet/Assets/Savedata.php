<?php
namespace Assets;
// require 'Dbconnect.php';
class Savedata
{
    //create table method
    function createTable($tableName,$data)
    {
        require 'Dbconnect.php';
        $query = "CREATE TABLE `googlesheet`.`$tableName` (`id` INT NOT NULL AUTO_INCREMENT,";
        for ($i = 0; $i < count($data); $i = $i + 1) {
            $query .= " " . strtolower(str_replace(" ", "_", $data[$i])) .  " " . "VARCHAR" . "(255)" . " NOT NULL,";
        }
        $query .= "  PRIMARY KEY (`id`));";
        $result=mysqli_query($conn,$query);
        if($result) {
            return true;
        } else {
            return false;
        }
    }
    
    // method to check table exist of not
    function isTableExist($tableName)
    {
        require 'Dbconnect.php';
        $exist = mysqli_query($conn, "SHOW TABLES LIKE'" . $tableName . "'");
        if ($exist->num_rows) {
            return true;
        } else {
            return false;
        }
    }

    //method to insert data in DB
    function insertData($tableName,$head,$data)
    {
        require 'Dbconnect.php';
        // $head=$colunm;
        $query = "INSERT INTO `$tableName` ("; // query to insert data to db
        for ($i = 0; $i < count($head); $i = $i + 1) {
            $query .= " " . strtolower(str_replace(" ", "_", $head[$i])) .  "";
            if ($i < count($head) - 1) {
                $query .= ",";
            };
        }
        $query .= " ) VALUES";
        for ($i = 1; $i <= count($data); $i++) {
            $query .= "(";
            for ($j = 0; $j <= count(($head))-1; $j++) {
                $query .= '\'' .  $data[$i][$j] . '\'';
                if ($j < count($head) - 1) {
                    $query .= ",";
                }
            }
            if ($i <=count($data) - 1) {
                $query .= "),";
            }
        }
        $query .= "  );";    
        $result = mysqli_query($conn, $query);
        if($result) {
            return true;
        }else{
            return false;
        }
    }
    // Add single data into db
    public function addSingleData($tableName,$head,$data){
        require 'Dbconnect.php';
        $query = "INSERT INTO `$tableName` ("; // query to insert data to db
        for ($i = 0; $i < count($head); $i++) {
            $query .= " " . strtolower(str_replace(" ", "_", $head[$i])) .  "";
            if ($i < count($head) - 1) {
                $query .= ",";
            };
        }
        $query .= " ) VALUES(";
        for ($i = 0; $i < count($head); $i++) {
            $query .= "'";
            $query.=$data[$i];
            $query .= "'";
            if ($i <=count($data) - 1) {
                $query .= ",";
            }
        }
        $query=rtrim($query,',');
        $query .= "  );";
        $result=mysqli_query($conn,$query);
        return $result;
    }
    // fetch max id from db
    public function fetchMaxId($tableName){
        require 'Dbconnect.php';
        $query="SELECT * FROM $tableName WHERE id = ( SELECT MAX(id) FROM $tableName) ;";
        $result=mysqli_query($conn,$query);
        while($row=mysqli_fetch_assoc($result)){
            $maxId=$row['id'];
        }
        return $maxId;
    }

    // featch all data 
    public function fetchData($tableName)
    {
        require 'Dbconnect.php';
        $sql="SELECT * FROM $tableName";
        $result=mysqli_query($conn,$sql);
        return $result;
    }

    // fetch data by id from Db
    public function fetchById($id,$tableName)
    {
        require 'Dbconnect.php';
        $query="SELECT * FROM $tableName WHERE `id`=$id";
        $result=mysqli_query($conn,$query);
        $editData=[];
        while($row=mysqli_fetch_assoc($result)) {
            $editData[]=$row;
        }
        return $editData;
    }
    // build sql query for updating data 
    function buildSqlUpdate($table, $data, $where)
    {
        require 'Dbconnect.php';
        $cols = array();
        $count=1;
        foreach($data as $key=> $val) {
            $cols[] = "$key = '$val'";
        }
        unset($cols[0]);
        $sql = "UPDATE $table SET " . implode(', ', $cols) . " WHERE `id`=$where";
        return($sql);
    }

    // update data in db
    public function updateDataInDb($query){
        require 'Dbconnect.php';
        return($result=mysqli_query($conn,$query));
    }

    // fetch column name
    public function fetchRow($tableName){
        require 'Dbconnect.php';
        $sql = "SELECT COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='$tableName'";
        $result = mysqli_query($conn, $sql);
        $rowArr = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $rowArr[] = $row['COLUMN_NAME'];
        }
        return $rowArr;
    }
    // delete table
    public function deleteTable($tableName){
        require 'Dbconnect.php';
        $query="DROP TABLE `$tableName`;";
        $result=mysqli_query($conn,$query);
        return $result;
    }
    // remove all null values
    public function removeNull($data){
        foreach ($data as $key => $value) {
            if ($value=="") {
                 $data[$key] = " ";
            }
        }
        return $data;
    }

    public function isAllColumExist($tableName,$column,$allData)  //working on that
    {
        // require 'Dbconnect.php';
        $sql = "SELECT COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME='$tableName'";
        $result = mysqli_query($conn, $sql);
        $columnArr = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if($row['COLUMN_NAME']=='id'){ continue; }
            $columnArr[] = $row['COLUMN_NAME'];
        }
        $data=[];
        foreach($column as $head) {
            $data[]=strtolower(str_replace(" ", "_", $head));
        }
        $diff=array_diff($data,$columnArr);
        $query= "ALTER TABLE `$tableName`"; foreach($diff as $data){
            $query.="ADD `$data` VARCHAR(50) NOT NULL ,";
        }
        $query= rtrim($query, ", ");
        $result=mysqli_query($conn,$query);
        $rowStart=array_keys($diff)[0];
        end($diff);
        $rowEnd=key($diff);
        array_splice($diff, 0, 0);
        $query = "INSERT INTO `$tableName` (";
        for ($i = 0; $i < count($diff); $i = $i + 1) {
            $query .= " " . strtolower(str_replace(" ", "_", $diff[$i])) .  "";
            if ($i < count($diff) - 1) {
                $query .= ",";
            };
        }
        $query .= " ) VALUES(";

        for ($i = 1; $i <= count($allData); $i++) {
            $query .= "(";
            for ($j = $rowStart; $j <= $rowEnd; $j++) {
                $query .= '\'' . strtolower(str_replace(" ", "_", $allData[$i][$j])) . '\'';
                if ($j < $rowEnd) {
                    $query .= ",";
                }
            }
            if ($i <=count($allData) - 1) {
                $query .= "),";
            }
        }
        $query=rtrim($query,',');
        $query .= "));";
        echo $query;
        $result=mysqli_query($conn,$query);
        var_dump($result);
    }
}
