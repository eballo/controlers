<?php
session_start();
if (! $_SESSION['login']){
	include "login.php";
	die;
}


?>