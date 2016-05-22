<?php
	include '../core/init.php';
	$current='login';
	logged_in_redirect();
	include '../includes/header.php';
?>
<title>EACC : Recover</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	$required_fields=array('email','password');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		if(empty($errors)===true)
		{	$email=$_POST['email'];
			$password=md5($_POST['password']);
			$query='SELECT user_id,username FROM `users` WHERE email=\''.$email.'\' AND password=\''.$password.'\'';
			$result=mysqli_query($GLOBALS['conn'],$query);
			if(mysqli_num_rows($result)!=1)
				$errors[]='Sorry, the email or password you entered is incorrect .<br/>Please fill the correct details .....';
			else
			{	$result=mysqli_fetch_assoc($result);
				$_SESSION['user_id']=$result['user_id'];				
				header('Location: ../pages/view.php?user='.$result['username']);
				exit();
				}
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			Recover Username
		</div>
		<div class="box-type-1"> 
			<div class="login-form"> 
				<?php
					if(empty($errors)===false)
					{	echo output_errors($errors);
						}
					?>
				<div class="login-form-inner"> 
					<header>Recover</header>
					<form method="post" action="">
						<input type="text" name="email" placeholder="Registered Email*" />
						<input type="password" name="password" placeholder="Password*" />
						<input type="submit" class="submit" value="Login" />
					</form>
				</div>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>