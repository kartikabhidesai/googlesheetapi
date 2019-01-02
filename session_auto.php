<?php 
	// session_start();
	date_default_timezone_set("Asia/Kolkata");
	$start_time=$_SESSION['start_time'];

	$time_out=time()-$start_time;
    $timefix=1800;

        if($timefix < $time_out)
        {
        	
            unset($_SESSION['username']);
            session_destroy();
            header("location:index.php"); 
            exit;       
        }
        $_SESSION['start_time']=time();
?>