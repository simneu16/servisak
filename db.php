<?php
$mysqli = new mysqli("mariadb114.r1.websupport.sk", "VpAI2eDT", "rcZDML8St#", "NFfYVciF");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>