<?php 
	include '../core/init.php'; 
	protect_page();
	
	if(preg_match("/teach_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='result';
	include '../includes/header.php';
?>
<title>EACC : Results</title>
<?php
	include '../includes/top.php';
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Create Result</header>
			<?php
				include '../includes/details.php';
			?>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>