<?php
$server = "localhost";
$user = "root";
$pass = "";
$database = "gas_alam_sentosa";

$conn = new mysqli($server, $user, $pass, $database);

if (!$conn) {
    die("<script>alert('Cannot connect to database')</script>");
}
