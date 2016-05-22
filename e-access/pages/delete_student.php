<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:../pages/access.php');
	$current='access';
	$here='delete';
		
	if(empty($_GET['enroll'])||empty($_GET['year'])||empty($_GET['branch'])||empty($_GET['sem'])||empty($_GET['batch']))
		header('Location:../pages/view.php');
	
	delete_student($_GET['enroll'],$_GET['year'],$_GET['branch'],$_GET['sem'],$_GET['batch']);
	header('Location:../pages/edit_details.php?delete=true&success');	
	
?>
