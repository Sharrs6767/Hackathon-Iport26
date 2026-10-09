<?php
$config = parse_ini_file(__DIR__ . "\..\config.ini", true);

$con = new mysqli($config["database"]["hostname"],$config["database"]["username"],$config["database"]["dbpass"],$config["database"]["dbname"]);
if(!$con){
    die(mysqli_error($con));
}
?>