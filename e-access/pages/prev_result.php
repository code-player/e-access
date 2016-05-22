<?php 
	include '../core/init.php'; 
	protect_page();
	$current='access';
	$here='view_result';
	include '../includes/header.php';
?>
<title>EACC : Batches</title>
<?php
	include '../includes/top.php';
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>View Results</header>
			<?php
				include '../includes/prev_details.php';
			?>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>