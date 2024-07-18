<?php

$link = mysqli_connect("localhost", "root", "", "chattingapp");
if (!($link)) {

    die(mysqli_connect_error());
}
function delete($tablename, $columnname, $value)
{
    global $link;
    try {

        $deleteQuery = "DELETE FROM $tablename WHERE $columnname = '$value'";
        $result = mysqli_query($link, $deleteQuery);
        if (!$result) {
            throw new Exception("Error deleting record: " . mysqli_error($link));
        }
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}

function insert($tablename, $data)
{

    global $link;
    try {
        $columnname = array_keys($data);
        $value = array_values($data);
        $finalcolumns = implode(',', $columnname);
        $finalvalues = "'" . implode("','", $value) . "'";

        $insertQuery = "INSERT INTO $tablename ($finalcolumns) VALUES ($finalvalues)";
        $result = mysqli_query($link, $insertQuery);
        if ($result) {
            return "inserted";
        } else {
            throw new Exception("Error executing query: " . mysqli_error($link));
        }
    } catch (Exception $e) {
        return "Error: " . $e->getMessage();
    }
}


