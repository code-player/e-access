<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:../pages/access.php');
	$current='access';
	$here='delete';
		
	if(empty($_GET['sem'])||empty($_GET['branch'])||empty($_GET['subject']))
		header('Location:../pages/view.php');
	
	delete_subject($_GET['sem'],$_GET['branch'],$_GET['subject']);
	header('Location:../pages/sem_subject_details.php?delete=true&success');	
	
?>
