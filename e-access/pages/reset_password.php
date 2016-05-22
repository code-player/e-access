<?php
	$current='login';
	include '../core/init.php';
	logged_in_redirect();
	include '../includes/header.php';
?>
<title>EACC : Login</title>
<?php
	include '../includes/top.php';
	
	if(isset($_GET['user'])&&isset($_GET['code']))
	{	$username=$_GET['user'];
		$code=$_GET['code'];
		
		$result=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE username='$username' AND email_code='$code'");
		if(mysqli_num_rows($result)!=1)
			header('Location:../pages/index.php');
		
		if(empty($_POST)===false)
		{	$password=$_POST['password'];
			$re_password=$_POST['re_password'];
			if(empty($password)||empty($re_password))
				$errors[]='fields marked with * are required...';
			else if($password!==$re_password)											
				$errors[]='your passwords don\'t match...';
			else
			{	if(strlen($password)>32)
					$errors[]='password entered is too long';
				if(strlen($password)<6)
					$errors[]='password must be atleast 6 characters long';
				}
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			Reset your password !
		</div>
		<div class="box-type-1"> 
			<div class="login-form"> 
				<?php
					if(isset($_GET['success'])&&empty($_GET['success']))
					{	?>
							<article id="About">	
								<header>
									<div class="About-inside">
										<h2>	
											<br/>Your password has been reset successfully...<br/><br/>
											<h2 style="text-align:center">please <a href="../pages/login.php">login</a> to continue...</h2>
										</h2>
									</div>
								</header>
							</article>
						<?php
						}
					if(isset($_GET['user'])&&isset($_GET['code']))
					{	if(empty($_POST)===false&&empty($errors)===true)
						{	reset_password($username,$password);
							header('Location:../pages/reset_password.php?success');
							}
						else
							echo output_errors($errors);
						?>
							<div class="login-form-inner"> 
								<header> Reset Password </header>
								<form method="post" action="">
									<input type="password" name="password" placeholder="New Password*"/>
									<input type="password" name="re_password" placeholder="Confirm New Password*"/>
									<input type="submit" class="submit" value="Change Password" />
								</form>
							</div>
						<?php
						}
				?>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>