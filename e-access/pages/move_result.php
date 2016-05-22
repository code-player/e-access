<?php 
	include '../core/init.php'; 
	protect_page();
	
	if(preg_match("/admin/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='move';
	include '../includes/header.php';
	
?>
<title>EACC : Batches</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
		if(empty($_POST['batch'])===true)
			$errors[]='Please select a batch whose result is to be exported...';
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Select batch for which the result is to be exported...</header>
			<?php
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php 
								echo '<br/>The result for batch \''.$_GET['batch'].'\' has been exported successfully...<br/>';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
				
				if(empty($_POST)===false&&empty($errors)===true)
				{//	$x=check_complete_backup($_POST['batch']);
					if($x==1)
					{	?>
							<div id="alert">
								<?php 
									echo '<br/>The result for batch \''.$_POST['batch'].'\' can\'t be exported right now as some re-registration cases are still pending...<br/>';
								?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/view.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}	
					else
					{	$batch=$_POST['batch'];
						global $batch;
						$url='http://localhost/e-access/'.$_POST['batch'].'.sql';
						move_result($servername,$username,$password,$_POST['batch']);
						
						header('Location:'.$url);
						
					//	header('Location:../pages/move_result.php?batch='.$_POST['batch'].'&success');
						}
					}
			?>		
			<div class="columns-type-3">
				<?php
					if(empty($errors)===false)
					{	?>
							<div id="errors">
								<?php echo output_errors($errors);?>
							</div>
						<?php
						}
					$query='SELECT DISTINCT `TABLE_SCHEMA` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` LIKE \'____-____%\'';
					$result=mysqli_query($GLOBALS['conn'],$query);
					$result=mysqli_fetch_assoc($result);
				?>
				<form action="" method="post">
					<div class="column1">
						<select style="margin-left:10em" name="batch">
							<option value="" class="list1">--Select Batch--</option>
							<?php
								foreach($result as $x)
								{	?>
										<option value="<?php echo $x;?>" class="list1"><?php echo $x;?></option>
									<?php
									}
							?>
						</select><br/><br/>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Create Backup">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>