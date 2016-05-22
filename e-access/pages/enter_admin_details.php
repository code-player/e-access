<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/admin/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='account';
	include '../includes/header.php';
?>
<title>EACC : Profile</title>
<?php
	include '../includes/top.php';
	
	if(isset($_GET['user'])===false||empty($_GET['user'])===false)
	{	if($_GET['user']!==$user_data['first_name'])
			header('Location:access.php');
		}
		
	if(empty($_POST)===false)
	{	$required_fields=array('first_name','department');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		if($_POST['first_name']===$user_data['first_name']&&$_POST['last_name']===$user_data['last_name']&&$_POST['department']===$user_data['department'])
			$errors[]='Currently admin\'s rights belong to this user only...';
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Enter new admin's details</header>
			<div id="errors">
				<em>Warning </em>:  This will withdraw all the admin's right from this account.<br/>
									The action is irreversible also...
			</div>
			<div class="columns-type-3">
				<?php
					if(empty($_POST)===false&&empty($errors)===true)
					{	set_user($_POST['first_name'],$_POST['last_name'],$_POST['department'],'admin');
						header('Location:../pages/view.php?done');
						}
					else if(empty($errors)===false)
					{	?>
							<div id="errors">
								<?php	echo output_errors($errors);?>
							</div>
						<?php
						}
				?>
				<form action="" method="post">
					<div class="column1">
						<p>First Name*:</p>
						<p>Last Name:</p>
						<p>Department*:</p>
					</div>
					<div class="column2">
						<input type="text" name="first_name" placeholder="First Name">
						<input type="text" name="last_name" placeholder="Last Name">
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
							<option value="Mathematics Department" class="list1">Mathematics</option>
							<option value="Physics" class="list1">Physics</option>
						</select>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Change Admin">
					</div>
				</form>
			</div>
			<div id="errors">
				<em>Note </em>: If you are neither any semester coordinator nor teacher for any semester.<br/>
								Then, this account will be deleted ...
			</div><br/>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>