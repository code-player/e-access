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

	if(isset($_GET['sem'])===true&&empty($_GET['sem'])===false)
	{	$sem=$_GET['sem'];
		$branch=$_GET['branch'];
		
		$table='sem_'.$sem.'_'.$branch;
		$query= 'CREATE TABLE IF NOT EXISTS `'.$table.'`
			(	`stu_id` int NOT NULL auto_increment,
				`enroll` varchar(32) NOT NULL,
				`batch` int NOT NULL,
				PRIMARY KEY  (`stu_id`)	
               );';
			
		mysqli_query($GLOBALS['conn1'],$query);
		
		$subject=array();
		$branch_subjects=get_subjects($_GET['sem'],$_GET['branch']);
		foreach(explode(',',$branch_subjects['course_name']) as $key=>$value)
			if($value===$_GET['subject'])
			{	$subject['course_name']=$value;
				$subject['course_code']=explode(',',$branch_subjects['course_code'])[$key];
				$subject['credit']=explode(',',$branch_subjects['credits'])[$key];
				}
				
		$url='../pages/delete_subject.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&subject='.$_GET['subject'];
		}
	
	if(empty($_POST)===false)
	{	if(empty($_POST['subject'])||empty($_POST['code'])||$_POST['credit']==='')
			$errors[]='Fields marked with * are required...';
		if(empty($errors)===true)
		{	if(preg_match("/^[a-zA-Z0-9\s]+$/",$_POST['subject'])==false)
				$errors[]='Course name must not contain any special character'; 
			if(preg_match("/^[a-zA-Z0-9\s]+$/",$_POST['code'])==false)
				$errors[]='Course code must not contain any special character'; 
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
						<header>Enter Subject Details to replace <?php echo $_GET['subject'];?> for <?php echo $sem;?> Semester</header>	
					<?php
					}	
				else	
				{	?>
						<header>Enter New Subject Details for <?php echo $sem;?> Semester</header>	
					<?php
					}	
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	if(empty($_GET['delete']))
					{	?>
							<div id="alert">
								<?php	
									echo '<br/>Semester subject for has been set successfully...<br/>' ;
									$url='../pages/set_subjects.php';
								?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/view.php'" value="Home">
									<input type="button" onclick="location.href='<?php echo $url;?>'" value="Set another Subject">
								</div><br/>
							</div>
						<?php
						}
					else if($_GET['delete']==='true')
					{	?>
							<div id="alert">
								<?php echo '<br/>Semester subject has been deleted successfully...'; ?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/view.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}
					}
				if(empty($_POST)===false && empty($errors)===true)
				{	$_GET['branch']=sanitize($_GET['branch']);
					$_GET['sem']=sanitize($_GET['sem']);
					$query='SELECT * FROM `subject_details` WHERE branch=\''.$_GET['branch'].'\' AND sem=\''.$_GET['sem'].'\'';
					$result=mysqli_query($GLOBALS['conn'],$query);
					$result=mysqli_fetch_assoc($result);
					if(isset($_GET['subject']))
					{	change_subject($sem,$branch,$_POST['subject'],$_POST['code'],$_POST['credit'],$_GET['subject']);
						header('Location:../pages/sem_subject_details.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&success');
						}
					else
					{	$_POST['subject']=trim($_POST['subject']);
						$_POST['code']=trim($_POST['code']);
						if(in_array($_POST['subject'],explode(',',$result['course_name']))&&in_array($_POST['code'],explode(',',$result['course_code'])))
						{	?>
								<div id="alert">
									<?php 
										echo '<br/>This subject has already been set....<br/>';
										$url='../pages/sem_subject_details.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&subject='.$_POST['subject'];
									?>
									<div class="buts">
										<input type="button" onclick="location.href='<?php echo $url;?>'" value="Change subject Details">
										<input type="button" onclick="location.href='../pages/view.php'" value="Home">
									</div><br/>
								</div>
							<?php
							}
						else
						{	set_subjects($sem,$branch,$_POST['subject'],$_POST['code'],$_POST['credit']);
							header('Location:../pages/sem_subject_details.php?success');
							}
						}
					}
			?>
			<div class="columns-type-3">
				<div id="errors">
					* Please add Lab/Workshop in the course name for labs/workshops respectively
				</div>
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
						<p>Course Code* :</p>
						<p>Credits* :</p>
					</div>
					<div class="column2">
						<?php
							if(empty($_GET['subject'])===false)
							{	?>
									<input type="text" name="subject" value="<?php echo $subject['course_name'];?>"><br/>
									<input type="text" name="code" value="<?php echo $subject['course_code'];?>"><br/>
									<input type="number" min=0 name="credit" value="<?php echo $subject['credit'];?>"><br/><br/>
								<?php
								}
							else
							{	?>
									<input type="text" name="subject" placeholder="Course name"><br/>
									<input type="text" name="code" placeholder="Course code"><br/>
									<input type="number" min=0 name="credit" placeholder="Credits"><br/><br/>
								<?php
								}
						?>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Set subject"><br/><br/>
						<?php	
							if(isset($_GET['subject']))
							{	?>
									<input type="button" onclick="location.href='<?php echo $url;?>'" value="Delete Subject"/>
								<?php
								}
						?>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>