<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='sem_teachers';
	include '../includes/header.php';
?>
<title>EACC : Teachers</title>
<?php
	include '../includes/top.php';
	
	if(isset($_GET['sem'])===true&&empty($_GET['sem'])===false)
	{	$sem=$_GET['sem'];
		$branch=$_GET['branch'];
		}
	
	$branch_subject=get_subjects($sem,$branch);
	$branch_subject=explode(',',$branch_subject['course_name']);
	
	if(empty($_POST)===false)
	{	$required_fields=array('subject','first_name','department');
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
				if(isset($_GET['subject']))
				{	?>
						<header>Enter Teacher's Details to replace the current teacher for <?php echo $_GET['subject'];?> for <?php echo $sem;?> Semester</header>	
					<?php
					}	
				else	
				{	?>
						<header>Enter New Teacher's Details for <?php echo $sem;?> Semester</header>	
					<?php
					}	
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php	
								echo '<br/>Semester subject teacher for has been set successfully...<br/>' ;
								$url='../pages/set_teachers.php';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="Home">
								<input type="button" onclick="location.href='<?php echo $url;?>'" value="Set another Teacher">
							</div><br/>
						</div>
					<?php
					}
				if(empty($_POST)===false && empty($errors)===true)
				{	if(isset($_GET['subject'])===false)
					{	$value=check_desc('teach_'.$branch.'_'.$sem.'_'.$_POST['subject']);
				
						if(mysqli_num_rows($value)!==0)
						{	$value=mysqli_fetch_assoc($value);
							?>
								<div id="alert">
									<?php	
										echo '<br/>'.$value['first_name'].' '.$value['last_name'].' of '.$value['department'].' department is already the teacher for this subject<br/>';
										$url='../pages/sem_teach_details.php?branch='.$branch.'&sem='.$sem.'&subject='.$_POST['subject'];
									?>
									<div class="buts">
										<input type="button" onclick="location.href='../pages/view.php'" value="Home">
										<input type="button" onclick="location.href='<?php echo $url;?>'" value="Change Subject Teacher">
									</div><br/>
								</div>
							<?php
							}
						else
						{	set_user($_POST['first_name'],$_POST['last_name'],$_POST['department'],'teach_'.$branch.'_'.$sem.'_'.$_POST['subject'].'_');
							header('Location:../pages/sem_teach_details.php?branch='.$branch.'&sem='.$sem.'&subject='.$_POST['subject'].'&success');
							}
						}
					else
					{	set_user($_POST['first_name'],$_POST['last_name'],$_POST['department'],'teach_'.$branch.'_'.$sem.'_'.$_POST['subject'].'_');
						header('Location:../pages/sem_teach_details.php?success');
						}
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
						<p>Course Name* :</p>
						<p>First Name* :</p>
						<p>Last Name :</p>
						<p>Department* :</p>
					</div>
					<div class="column2">
						<?php	
							if(isset($_GET['subject'])===true)
							{	?>
									<input type="text" name="subject" value="<?php echo $_GET['subject'];?>" readonly>
								<?php
								}
							else
							{	?>
									<select name="subject">
										<option value="" class="list1">-- Select Subject --</option>
											<?php
												foreach($branch_subject as $x)
												{	?>
														<option value="<?php echo $x; ?>" class="list1"><?php echo $x;?></option>
													<?php
													}
											?>
									</select>
								<?php
								}
						?>
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
						</select><br/><br/><br/>
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