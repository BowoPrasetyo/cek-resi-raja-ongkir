<?php
$conf = array();
$conf['localhost'] = 'localhost';
$conf['username'] = 'u6458748_cekresiuser';
$conf['password'] = 'sadeltoe_94';
$conf['dbname'] = 'u6458748_cekresi';

$conn = new mysqli($conf['localhost'], $conf['username'], $conf['password'], $conf['dbname']);

if(!$conn) {	
	echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
}