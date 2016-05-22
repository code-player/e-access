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
	
	if(isset($_GET['sem'])===true&&empty($_GET['sem'])===false)
	{	$sem=$_GET['sem'];
		$branch=$_GET['branch'];
		}
	if(empty($_POST)===false)
	{	$required_fields=array('first_name','department');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<?php
				if(isset($_GET['name']))
				{	?>
						<header>Enter Semester Coordinator's Details to replace the current coordinator for <?php echo $sem;?> Semester</header>	
					<?php
					}
				else
				{	?>
						<header>Enter New Semester Coordinator's Details for <?php echo $sem;?> Semester</header>	
					<?php
					}
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php	
								echo '<br/>Semester coordinator for has been set successfully...<br/>' ;
								$url='../pages/set_coords.php';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='<?php echo $url;?>'" value="Set another coordinator">
								<input type="button" onclick="location.href='../pages/view.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
			?>
			<div class="columns-type-3">
				<?php
					if(empty($_POST)===false && empty($errors)===true)
					{	set_user($_POST['first_name'],$_POST['last_name'],$_POST['department'],'coord_'.$branch.'_'.$sem.'_');
						header('Location:../pages/sem_coords_details.php?success');
						}
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
						<p>First Name* :</p>
						<p>Last Name :</p>
						<p>Department* :</p>
					</div>
					<div class="column2">
						<input type="text" name="first_name" placeholder="First name">
						<input type="text" name="last_name" placeholder="Last name">
						<select name="department">
							<option value="" class="list1">-- Select Department* --</option>	
							<option value="Information Technology" class="list1">Information Technology</option>
							<option value="Computer Science" class="list1">Computer Science</option>
							<option value="Electronics And Communication" class="list1">Electronics And Communication</option>
							<option value="Mechanical Engineering" class="list1">Mechanical Engineering</option>
							<option value="Metallurgy Engineering" class="list1">Metallurgy Engineering</option>
							<option value="Electrical Engineering" class="list1">Electrical Engineering</option>
							<option value="Civil Engineering" class="list1">Civil Engineering</option>
							<option value="Chemical Engineering" class="list1">Chemical Engineering</option>
							<option value="Mathematics" class="list1">Mathematics</option>
							<option value="Physics" class="list1">Physics</option>
							<option value="Chemistry" class="list1">Chemistry</option>
							<option value="Humanities" class="list1">Humanities</option>
						</select>
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