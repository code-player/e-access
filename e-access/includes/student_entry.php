<?php
	protect_page();
	if(empty($_POST)===false)
	{	$required_fields=array('enroll','batch','first_name','email','sem','branch');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		if(empty($errors)===true)
		{	$enroll=explode('/',$_POST['enroll']);
			if((is_numeric($enroll[0])===false||preg_match("/\A\d\d\z/",$enroll[1])==false)&&empty($enroll[2]))
				$errors[]='enrollment must be of the form \'enroll/year\' as for \'it/1/13\' must enter \'1/13\' only...';
			else if(preg_match("/\A\d\d\d\d\z/",$_POST['batch'])==false)
				$errors[]='batch must corresponds with the year of admission...';
			else if($_POST['batch']>date('Y')||((2*(date('Y')-$_POST['batch'])+1)>8)&&date('m')>6)
				$errors[]='invalid batch entered...';
			if(($_POST['enroll'][strlen($_POST['enroll'])-2]!==$_POST['batch'][2])||($_POST['enroll'][strlen($_POST['enroll'])-1]!==$_POST['batch'][3]))
				$errors[]='enroll does not corresponds with batch...';
			if(student_exists($_POST['enroll'],$_POST['sem'],$_POST['branch'])===true)
					$errors[]='Sorry the student\'s record with enroll \''.$_POST['branch'].'/'.$_POST['enroll'].'\' already exists...';
			if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)===false)
				$errors[]='A valid email address is required';
			if(student_email_exists($_POST['email'],$_POST['sem'])===true)
				$errors[]='Sorry the email \''.$_POST['email'].'\' already exists...';
			
			if(empty($coord_sem)===false)
			{	$temp=0;
				if(in_array($_POST['sem'],$coord_sem)===true)
				{	if(empty($coord_branch)===false)
					{	foreach($coord_branch as $x)
							if($x===$_POST['branch'])
								$temp=1;
						}
					}
				if($temp===0)
					$errors[]='You don\'t have permission to enroll for this semester/branch...';
				}
			else
				$errors[]='You don\'t have permission to enroll for this semester/branch...';
			}
		}
		
	if(isset($_GET['success'])&&empty($_GET['success']))
	{	?>
			<div id="alert">
				<?php echo "<br/>Student has been registered successfully...!!!<br/>"; ?>
				<div class="buts">
					<input type="button" onclick="location.href='../pages/view.php'" value="Ok"/>
				</div><br/>
			</div>
		<?php
		}
	
	if(empty($_POST)===false&&empty($errors)===true)
	{	$register_data=array(
						'enroll'		=>  $_POST['enroll'],
						'batch'			=>  $_POST['batch'],
						'first_name'	=>  $_POST['first_name'],
						'last_name'		=>  $_POST['last_name'],
						'email'			=>  $_POST['email'],
						'phone'			=>  $_POST['phone'],
						'sem'			=>  $_POST['sem'],
						'branch'		=>  $_POST['branch']
						);
		register_student($register_data);
		header('Location:create_new.php?success'); 
		exit();
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
			<input type="text" name="enroll" placeholder="Enrollment*<as 01/13>"/>
			<input type="text" name="first_name" placeholder="First name*"/>
			<input type="text" name="email" placeholder="Email*"/>
			<select name="sem" id="ssemester">
				<option value="" class="list1">-- Select Semester* --</option>
				<option value="III" class="list1">Semester III</option>
				<option value="IV" class="list1">Semester IV</option>
				<option value="V" class="list1">Semester V</option>
				<option value="VI" class="list1">Semester VI</option>
				<option value="VII" class="list1">Semester VII</option>
				<option value="VIII" class="list1">Semester VIII</option>
			</select>
		</div>
		<div class="column2">
			<input type="text" id="sbatch" name="batch" placeholder="Batch*" onchange="set_sem()"/>
			<input type="text" name="last_name" placeholder="Last name"/>
			<input type="text" name="phone" placeholder="Contact"/>
			<select name="branch">
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
			<input type="submit" name="student_entry" value="Add">
		</div>
	</form>
</div>