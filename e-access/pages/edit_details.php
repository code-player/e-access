<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:../pages/access.php');
	$current='access';
	$here='create';
	include '../includes/header.php';
?>
<title>EACC : e-Register</title>
<?php
	include '../includes/top.php';
	
	if(isset($_GET)===false)
		header('Location:../pages/view.php');
	
	if(empty($_GET['sem'])===false&&empty($_GET['enroll'])===false&&empty($_GET['branch'])===false)
	{	$year='sem_'.$_GET['sem'];
		$query='SELECT stu_id,batch,first_name,last_name,email,phone,branch FROM '.$year.' WHERE enroll=\''.$_GET['enroll'].'\'AND sem=\''.$_GET['sem'].'\' AND branch=\''.$_GET['branch'].'\'';
		$result=mysqli_query($GLOBALS['conn'],$query);
		$result=mysqli_fetch_assoc($result);
		
		$url='../pages/delete_student.php?enroll='.$_GET['enroll'].'&year='.$year.'&branch='.$result['branch'].'&sem='.$_GET['sem'].'&batch='.$result['batch'];
		}
	
	if(empty($_POST)===false)
	{	$required_fields = array('first_name','email','branch');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		if(isset($_POST)&&empty($errors)===true)
		{	if($_POST['branch']!==$_GET['branch'])
				if(student_exists($_GET['enroll'],$_GET['sem'],$_POST['branch'])===true)
					$errors[]='Sorry the student\'s record with enroll \''.$_POST['branch'].'-'.$_GET['enroll'].'\' already exists...';
			if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false)
				$errors[]='A valid email address is required';
			else if(email_exists($_POST['email'])===true && $user_data['email']!==$_POST['email'])
				$errors[]='Sorry the email \''.$_POST['email'].'\' is already in use.';
			}
		}
	
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Edit student details</header>
			<?php
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	if(empty($_GET['delete']))
					{	?>
							<div id="alert">
								<?php echo '<br/>Students\' details has been updated successfully...'; ?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/view.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}
					else if($_GET['delete']==='true')
					{	?>
							<div id="alert">
								<?php echo '<br/>Students\' details has been deleted successfully...'; ?>
								<div class="buts">
									<input type="button" onclick="location.href='../pages/view.php'" value="OK">
								</div><br/>
							</div>
						<?php
						}
					}
				if(empty($_POST)===false&&empty($errors)===true)
				{	$update_data=array(
									'enroll'		=>  $_GET['enroll'],
									'cur_branch'	=>  $_GET['branch'],
									'first_name'	=>  $_POST['first_name'],
									'last_name'		=>  $_POST['last_name'],
									'email'			=>  $_POST['email'],
									'phone'			=>  $_POST['phone'],
									'sem'			=>  $_GET['sem'],
									'branch'		=>  $_POST['branch']
									);
					update_student($update_data);
					header('Location:edit_details.php?success'); 
					}
				else if(empty($errors)===false)
				{	?>
						<div id="errors">
							<?php	echo output_errors($errors);?>
						</div>
					<?php
					}
			?>		
			<div class="columns-type-3">
				<form action="" method="post">	
					<div class="column1">
						<p>First Name* :</p>
						<p>Last Name :</p>
						<p>Email* :</p>
						<p>Contact :</p>
						<p>Branch* :</p>
					</div>
					<div class="column2">
						<input type="text" name="first_name" value="<?php echo $result['first_name'];?>">
						<input type="text" name="last_name" value="<?php echo $result['last_name'];?>">
						<input type="text" name="email" value="<?php echo $result['email'];?>">
						<input type="text" name="phone" value="<?php echo $result['phone'];?>">
						<select name="branch" id="sbranch">
							<option value="<?php echo $_GET['branch'];?>" class="list1"><?php echo $_GET['branch'];?></option>
							<?php
								if($_GET['branch']!=='Information Technology')
								{	?>
										<option value="Information Technology" class="list1">Information Technology</option>
									<?php
									}
								if($_GET['branch']!=='Computer Science')
								{	?>
										<option value="Computer Science" class="list1">Computer Science</option>
									<?php
									}
								if($_GET['branch']!=='Electronics And Communication')
								{	?>
										<option value="Electronics And Communication" class="list1">Electronics And Communication</option>
									<?php
									}
								if($_GET['branch']!=='Mechanical Engineering')
								{	?>
										<option value="Mechanical Engineering" class="list1">Mechanical Engineering</option>
									<?php
									}
								if($_GET['branch']!=='Metallurgy Engineering')
								{	?>
										<option value="Metallurgy Engineering" class="list1">Metallurgy Engineering</option>
									<?php
									}		
								if($_GET['branch']!=='Electrical Engineering')
								{	?>
										<option value="Electrical Engineering" class="list1">Electrical Engineering</option>
									<?php
									}		
								if($_GET['branch']!=='Civil Engineering')
								{	?>
										<option value="Civil Engineering" class="list1">Civil Engineering</option>
									<?php
									}		
								if($_GET['branch']!=='Chemical Engineering')
								{	?>
										<option value="Chemical Engineering" class="list1">Chemical Engineering</option>
									<?php
									}		
							?>
						</select>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Update"/><br/><br/>
						<input type="button" onclick="location.href='<?php echo $url;?>'" value="Delete Record"/>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>