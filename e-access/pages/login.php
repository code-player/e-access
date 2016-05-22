<?php
	$current='login';
	include '../core/init.php';
	logged_in_redirect();
	include '../includes/header.php';
?>
<title>EACC : Login</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	$username=$_POST['username'];
		$password=$_POST['password'];
		if(empty($username)||empty($password))
			$errors[]='fields marked with * are required...';
		else if(user_exists($username)===false)											
			$errors[]='user does not exists... please register first...!!!';
		else if(user_active($username)===false)
			$errors[]='the account is not activated yet...<br/>please check your email to activate the account...';
		else
		{	if(strlen($password)>32)
			{	$errors[]='password entered is too long';
				}
			$login=login($username,$password);
			if($login===false)
				$errors[]='please enter correct username and password...';
			else
			{	$_SESSION['user_id']=$login;					
				header('Location: ../pages/access.php');
				exit();
				}
			}
		}	
?>
	<div class="content">
		<div class="intro"> 
		 Access your Account here ! 
		</div>
		<div class="box-type-1"> 
			<div class="login-form"> 
				<?php
					if(empty($errors)===false)
					{	echo output_errors($errors);
						}
				?>
				<div class="login-form-inner"> 
					<header> Login </header>
					<form method="post" action="">
						<input type="text" name="username" placeholder="Username*"/>
						<input type="password" name="password" placeholder="Password*"/>
						<input type="submit" class="submit" value="Login" />
						<input onclick="location.href='../pages/register.php'" type="button" class="submit" value=" Register ?"/>
					</form>
					<br/><hr/>
					<a href="../pages/recover_username.php"><div class="forgot">Forgot Username ?</div></a>
					<a href="../pages/recover_password.php"><div class="forgot">Forgot Password ?</div></a>
				</div>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>