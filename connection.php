<?php
$conf = array();
$conf['localhost'] = 'localhost';
$conf['username'] = 'db_username';
$conf['password'] = 'password';
$conf['dbname'] = 'db_name';

$conn = new mysqli($conf['localhost'], $conf['username'], $conf['password'], $conf['dbname']);

if(!$conn) {	
	echo "Failed to connect to MySQL: (" . mysqli_connect_errno() . ") " . mysqli_connect_error();
}
