<?php


$con = mysqli_connect("localhost","root","Kwanduti2008#","mytestdb");

if(!$con){
	echo mysqli_connect_error();
	die();
}

echo "Database Connected Succefully";