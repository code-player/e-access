<?php
    include "../core/init.php";
	protect_page();
	$current='access';
	$here='account';
	include '../includes/header.php';
?>
<title>EACC : Profile</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	$required_fields = array('username','first_name','email');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		if(empty($errors)===true)
		{	if(user_exists($_POST['username'])===true&&$_POST['username']!==$user_data['username'])
				$errors[]='Sorry the username \''.$_POST['username'].'\' is already taken.';
			if(preg_match("/\\s/",$_POST['username'])==true)				
				$errors[]='your username must not contain any spaces...';
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
			<header>Update your account details</header>
			<div class="columns-type-3">
				<form action="" method="post">	
					<div class="column1">
						<p>Username* :</p>
						<p>First Name* :</p>
						<p>Last Name :</p>
						<p>Email* :</p>
						<p>Contact* :</p>
					</div>
					<div class="column2">
						<input type="text" name="username" value="<?php echo $user_data['username'];?>">
						<input type="text" name="first_name" value="<?php echo $user_data['first_name'];?>">
						<input type="text" name="last_name" value="<?php echo $user_data['last_name'];?>">
						<input type="text" name="email" value="<?php echo $user_data['email'];?>">
						<input type="text" name="phone" value="<?php echo $user_data['phone'];?>">
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>