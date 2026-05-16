<?php

    $host = "127.0.0.1";
    $dbuser = "root";
    $dbpassword = "";
    $dbname = "ocs";


    function getConnection()
{
        global $host;
        global $dbuser;
        $con = @mysqli_connect($host, $dbuser, $GLOBALS['dbpassword'], $GLOBALS['dbname']);
        
        //if (!$con) 
        //{
           // die("Database Connection Error: " . mysqli_connect_error() . "<br>Make sure MySQL is running in XAMPP.");
        //}
        
        return $con; 
    }

?>