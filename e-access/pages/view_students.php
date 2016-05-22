<?php 
	include '../core/init.php'; 
	protect_page();
	$current='access';
	$here='view';
	include '../includes/header.php';
?>

<title>EACC : Students</title>
<?php
	include '../includes/top.php';
	
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Enrolled Students</header>
		
			<?php
				include '../includes/search.php';
			
				GLOBAL $check;
				if($check!==1)	
				{	?>
						<div class="columns-type-2">	
							<div class="images">
								<img src="../images/erecords.jpg" alt="Records"/>
							</div>
						</div>
					<?php
					}
			?>	
			
		</div>
	</div>

<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>