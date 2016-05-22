<?php 
	include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='backup';
	
	if(!isset($_GET['sem'])||!isset($_GET['branch']))
		header('Location:../pages/create_backup.php');
	
	$sem=$_GET['sem'];
	$branch=$_GET['branch'];
	$val='coord_'.$branch.'_'.$sem.'_';
	
	if(preg_match("/$val/",$user_data['user_desc'])==false)
		header('Location:../pages/create_backup.php');
	
	$x=result_backup($sem,$branch);
	if($x===0)
		header('Location:../pages/create_backup.php?false');
	if($x===1)
		header('Location:../pages/create_backup.php?sem='.$sem.'&branch='.$branch.'&success');
	if($x===2)
		header('Location:../pages/create_backup.php?sem='.$sem.'&branch='.$branch.'&value=no');
?>