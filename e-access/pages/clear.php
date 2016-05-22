<?php
    include '../core/init.php';
	protect_page();
	$current='access';
	$here='clear';
		
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	
	include '../includes/header.php';
?>	
<title>EACC : Results</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	if(empty($_POST['sem'])===true)
			$errors[]='Please select a semester to set subjects for...';
		else if($_POST['branch']==='')
			$errors[]='Please select the branch to set subjects for...';
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Delete all the result records for the current semester</header>
			<?php
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php 
								echo '<br/>Result record for semester '.$_GET['sem'].' '.$_GET['branch'].' has been deleted successfully...<br/>'; 
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
				if(empty($_POST)===false&&empty($errors)===true)
				{	if(empty($coord_sem[1]))
						$_POST['sem']=$coord_sem[0];
					$sem=sanitize($_POST['sem']);								
					$branch=sanitize($_POST['branch']);							
					$val='coord_'.$branch.'_'.$sem.'_';
					$table='sem_'.$sem.'_'.$branch;
					$count=mysqli_query($GLOBALS['conn1'],"SELECT * FROM `$table` WHERE 1");
					if(preg_match("/$val/",$user_data['user_desc'])==false)
					{	?>	
							<div id="alert">
								<?php	
									echo '<br/>You don\'t have right to delete result for this semester/branch...<br/>';
								?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/clear.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}
					else if(mysqli_num_rows($count)<=0)
					{	?>
							<div id="alert">
								<?php 
									echo '<br/>No result exists for this branch and semester....<br/>';
									?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/clear.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}
					else
					{	$x=clear_result($sem,$branch);
						if($x==1)
						{	?>
								<div id="alert">
									<?php 
										echo '<br/>Result can\'t be deleted as only re-registration cases are available now.<br/>';
									?>
									<div class="buts">
										<input type="button" onclick="location.href='../pages/view.php'" value="OK">
									</div><br/>
								</div>
							<?php
							}
						else
						header('Location:../pages/clear.php?sem='.$sem.'&branch='.$branch.'&success');
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
				?>
				<form method="post" action="">
					<div class="column1">
						<?php
							if(empty($coord_sem[1])===false)
							{	$sem=array_unique($coord_sem);
								?>
									<select name="sem" id="sem" onclick="check_sem()">
										<option value="" class="list1">-- Select Semester* --</option>
										<?php
											foreach($sem as $x)
											{	?>
													<option value="<?php echo $x;?>" class="list1"><?php echo 'SEMESTETR '.$x;?></option>
												<?php
												}
										?>
									</select>
								<?php
								}
							else
							{	?>
									<input type="text" name="sem" id="sem" value="<?php echo 'SEMESTER '.$coord_sem[0]; ?>" readonly>
								<?php
								}
						?>
					</div>
					<div class="column2">
						<?php
							if(empty($coord_branch[1])===false)
							{	$branch=array_unique($coord_branch);
								?>
									<select name="branch" id="branch">
										<option value="" class="list1">-- Select Branch* --</option>
										<?php
											foreach($branch as $x)
											{	?>
													<option value="<?php echo $x;?>" class="list1"><?php echo $x;?></option>
												<?php
												}
										?>
									</select>
								<?php
								}
							else
							{	?>
									<input type="text" name="branch" id="branch" value="<?php echo $coord_branch[0]; ?>" readonly>
								<?php
								}
						?>
						<br/><br/>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Next">
					</div>
				</form>
			</div>
			<div id="errors">
				<?php
					echo '<br/>The action is irreversible...<br/><br/>
							   It is advisable to create a RESULT BACKUP first, if not created yet...<br/><br/>';
				?>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>