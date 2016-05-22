<?php 
	include '../core/init.php'; 
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='backup';
	include '../includes/header.php';
?>
<title>EACC : Subjects</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	if(empty($_POST['sem'])===true)
			$errors[]='Please select a semester to create result backup for...';
		else if($_POST['branch']==='')
			$errors[]='Please select the branch to create result backup for...';
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Select semester/branch to create backup for...</header>
			<?php
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php 
								echo '<br/>The backup has been created successfully for '.$_GET['sem'].' SEMESTER '.$_GET['branch'].'<br/>';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
				else if(isset($_GET['false'])&&empty($_GET['false']))
				{	?>
						<div id="alert">
							<?php	
								echo '<br/>No result exists for this semester/branch...<br/>';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/create_backup.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
				else if(isset($_GET['value'])&&$_GET['value']==='no')
				{	?>
						<div id="alert">
							<?php	
								echo '<br/>Backup can\'t be created as only re-registration cases are available for this semester/branch...<br/>';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/create_backup.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
				if(empty($_POST)===false && empty($errors)===true)
				{	if(empty($coord_sem[1]))
						$_POST['sem']=$coord_sem[0];
					$val='coord_'.$_POST['branch'].'_'.$_POST['sem'].'_';
					if(preg_match("/$val/",$user_data['user_desc'])==false)
					{	?>	
							<div id="alert">
								<?php	
									echo '<br/>You don\'t have right to create backup for this semester/branch...<br/>';
								?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/create_backup.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}
					else
					{	$sem=$_POST['sem'];
						$branch=$_POST['branch'];
						$sem=sanitize($sem);
						$branch=sanitize($branch);
	
						$query='SELECT MAX(batch) FROM `sem_'.$sem.'_'.$branch.'` WHERE 1';
						$batch=mysqli_fetch_array(mysqli_query($GLOBALS['conn1'],$query));
						$batch=$batch[0];
						$name=$batch.'-'.($batch+4);
						
						$query='SELECT * FROM `'.$name.'`.`sem_'.$sem.'_'.$branch.'` WHERE 1';
						$result=mysqli_query($GLOBALS['conn'],$query);
						if(mysqli_num_rows($result)>0)
						{	$url='../pages/backing_up.php?sem='.$_POST['sem'].'$branch='.$_POST['branch'];
							?>
								<div id="alert">
									<?php	
										echo '<br/>Result backup has already been created for this semester/branch...<br/>';
									?>
									<div class="buts">
										<input type="button" onclick="location.href='../pages/view.php'" value="Cancel"/>
										<input type="button" onclick="location.href='../pages/backing_up.php?sem=<?php echo $_POST['sem'];?>&branch=<?php echo $_POST['branch'];?>'" value="Create New Backup"/>
									</div><br/>
								</div>
							</div>
							</div>
							<?php
								include '../includes/bottom.php';
								include '../includes/footer.php';
								exit();
							}
						header('Location:../pages/backing_up.php?sem='.$_POST['sem'].'&branch='.$_POST['branch']);
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
				<form action="" method="post">
					<div class="column1">
						<?php
							if(empty($coord_sem[1])===false)
							{	$sem=array_unique($coord_sem);
								?>
									<select name="sem" id="sem">
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