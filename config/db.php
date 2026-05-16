<?php

$host = "127.0.0.1";
$dbuser = "root";
$dbpassword = "";
$dbname = "ocs";

function getConnection()
{
    global $host;
    global $dbuser;
    $con = mysqli_connect($host, $dbuser, $GLOBALS['dbpassword'], $GLOBALS['dbname']);
    return $con;
}
?>
