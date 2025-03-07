<?php
$db_server = "localhost";
$db_user = "csc350";  
$db_pass = "xampp"; 
$db_name = "gameloot";

try{
    $dbc = mysqli_connect($db_server,$db_user,$db_pass,$db_name);
}
catch(mysqli_sql_exception){
    $dbErrorMessage = '';
    if (mysqli_connect_errno()) {
        $dbErrorMessage = "Unable to connect to the database. Please try again later.";
    }
    //echo '<p style="color: red;">' . mysqli_connect_error() . '</p>';
    //echo '<p style="color: red;">Ensure that you are properly connected to the database server.</p>';
}
?>
