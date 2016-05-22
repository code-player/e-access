<?php 
	include '../core/init.php'; 
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='sem_subjects';
	include '../includes/header.php';
?>
<title>EACC : Subjects</title>
<?php
	include '../includes/top.php';
	
	if(empty($coord_sem[1])===true)
		header('Location:../pages/sem_subject_details.php?sem='.$coord_sem[0].'&branch='.$coord_branch[0]);
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
			<header>Select semester/branch to set subject for...</header>
			<div class="columns-type-3">
				<?php
					if(empty($_POST)===false && empty($errors)===true)
					{	header('Location:../pages/sem_subject_details.php?sem='.$_POST['sem'].'&branch='.$_POST['branch']);
						}
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
						<select name="sem" id="sem" onclick="check_sem()">
							<option value="" class="list1">-- Select Semester* --</option>
							<?php
								foreach($coord_sem as $x)
								{	?>
										<option value="<?php echo $x;?>" class="list1"><?php echo 'Semester '.$x;?></option>
									<?php
									}
							?>
						</select>
					</div>
					<div class="column2">
						<select name="branch" id="branch">
							<option value="" class="list1">-- Select Branch* --</option>
							<?php
								foreach($coord_branch as $x)
								{	?>
										<option value="<?php echo $x;?>" class="list1"><?php echo $x;?></option>
									<?php
									}
							?>
						</select><br/><br/>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="next">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>