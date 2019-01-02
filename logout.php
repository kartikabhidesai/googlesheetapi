<?php
session_start();
if(!empty($_SESSION['username']))
{
	$_SESSION['username']='';
	unset($_SESSION['username']);
    session_destroy();
}
header("Location:index.php");

?>
