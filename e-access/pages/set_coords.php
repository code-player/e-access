<?php 
	include '../core/init.php'; 
	protect_page();
	
	if(preg_match("/admin/",$user_data['user_desc'])==false)
		header('Location:access.php');
	
	$current='access';
	$here='sem_coords';
	include '../includes/header.php';
?>
<title>EACC : Coordinators</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	if(empty($_POST['sem'])===true)
			$errors[]='Please select a semester to set semester coordinator for...';
		else if(empty($_POST['branch']))
			$errors[]='Please select the branch to set semester coordinator for...';
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Select semester to set coordinator for...</header>
			<?php
				if(empty($_POST)===false && empty($errors)===true)
					{	$value=check_desc('coord_'.$_POST['branch'].'_'.$_POST['sem'].'_');
						if(mysqli_num_rows($value)!==0)
						{	$value=mysqli_fetch_assoc($value);
							?>
								<div id="alert">
									<?php	
										echo '<br/>'.$value['first_name'].' '.$value['last_name'].' of '.$value['department'].' department is already the semester coordinator for this semester<br/>';
										$url='../pages/sem_coords_details.php?branch='.$_POST['branch'].'&sem='.$_POST['sem'].'&name='.$value['first_name'].' '.$value['last_name'];
									?>
									<div class="buts">
										<input type="button" onclick="location.href='../pages/view.php'" value="Home">
										<input type="button" onclick="location.href='<?php echo $url;?>'" value="Change Semester Coordinator">
									</div><br/>
								</div>
							<?php
							}
						else
							header('Location:../pages/sem_coords_details.php?sem='.$_POST['sem'].'&branch='.$_POST['branch']);
						}
			?>
			<div class="columns-type-3">
				<?php
					if(empty($errors)===false)
					{	?>
						<div id="errors">
							<?php	echo output_errors($errors);?>
						</div>
						<?php
						}
				?>
				<form action="" method="post">
					<div class="column1">
						<select name="sem" id="sem" onclick="check_sem()">
							<option value="" class="list1">-- For Semester* --</option>
								<?php	
									if(date(m)>6)
									{	?>
											<option value="III" class="list1">Semester III</option>
											<option value="V" class="list1">Semester V</option>
											<option value="VII" class="list1">Semester VII</option>
										<?php
										}
									else
									{	?>
											<option value="IV" class="list1">Semester IV</option>
											<option value="VI" class="list1">Semester VI</option>
											<option value="VIII" class="list1">Semester VIII</option>
										<?php
										}
								?>
						</select>
					</div>
					<div class="column2">
						<select name="branch" id="branch">
							<option value="" class="list1">-- Select Branch* --</option>
							<option value="Information Technology" class="list1">Information Technology</option>
							<option value="Computer Science" class="list1">Computer Science</option>
							<option value="Electronics And Communication" class="list1">Electronics And Communication</option>
							<option value="Mechanical Engineering" class="list1">Mechanical Engineering</option>
							<option value="Metallurgy Engineering" class="list1">Metallurgy Engineering</option>
							<option value="Electrical Engineering" class="list1">Electrical Engineering</option>
							<option value="Civil Engineering" class="list1">Civil Engineering</option>
							<option value="Chemical Engineering" class="list1">Chemical Engineering</option>
						</select><br/><br/>
					</div>
					<div class="container-type-2-submit">	
						<input type="submit" value="Next">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>