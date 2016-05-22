<?php
	session_start();					
	error_reporting(0);					
	require 'database/connect.php';
    require 'functions/general.php';
	require 'functions/users.php';
	
	$cur_location=explode('/',$_SERVER['SCRIPT_NAME']);
	$cur_location=end($cur_location);
	
	if(logged_in()===true)
	{	$session_user_id=$_SESSION['user_id'];
		$user_data = user_data($session_user_id,'user_id','username','department','first_name','last_name','email','phone','password','user_desc');
		if(preg_match("/coord_/",$user_data['user_desc'])==true)
		{	$value=array();
			$coord_branch=array();
			$coord_sem=array();
			$desc=explode(',',$user_data['user_desc']);
			foreach($desc as $x)
			{	if(preg_match("/coord_/",$x)==true)
					$value[]=$x;
				}
			foreach($value as $x)
			{	$x=substr($x,6);
				$b=substr($x,0,strpos($x,'_'));
				$val=substr($x,strlen($b)+1);
				$coord_sem[]=substr($val,0,strpos($val,'_'));
				$coord_branch[]=$b;
				}
			}
		if(preg_match("/teach_/",$user_data['user_desc'])==true)
		{	$value=array();
			$teach_sem=array();
			$teach_branch=array();
			$teach_subject=array();
			$desc=explode(',',$user_data['user_desc']);
			foreach($desc as $x)
			{	if(preg_match("/teach_/",$x)==true)
					$value[]=$x;
				}
			foreach($value as $p)
			{	$p=substr($p,6);
				$b=substr($p,0,strpos($p,'_'));
				$val=substr($p,strlen($b)+1);
				$s=substr($val,0,strpos($val,'_'));
				$val=substr($val,strlen($s)+1);
				$teach_sem[]=$s;
				$teach_branch[]=$b;
				$teach_subject[]=substr($val,0,strpos($val,'_'));
				}
			}
	
		if(user_active($user_data['username'])===false)
		{	session_destroy();
			header('Location:index.php');
			exit();
			}
		}
	$errors = array();
	
?>
