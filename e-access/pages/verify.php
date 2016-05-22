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
	{	if(empty($_POST['username'])===true)
			$errors[]='Please enter your username...';
		if(empty($_POST['code']))
			$errors[]='Please enter the verification code you have received in your registered email....';
		else
		{	$username=sanitize($_POST['username']);
			$code=mysqli_query($GLOBALS['conn'],"SELECT email_code FROM users WHERE username='$username'");
			if(mysqli_num_rows($code)!=1)
				$errors[]='sorry, we couldn\'t find the username ,right now!!!<br/>please try again later ...
							<br/><br/>sorry for the inconvinence....';
			else
			{	$code=mysqli_fetch_assoc($code);
				if($code['email_code']!==$_POST['code'])
				{	$errors[]='Verification code doesn\'t found correct...';
					}
				else
					header('Location:../pages/reset_password.php?user='.$_POST['username'].'&code='.$_POST['code']);
				}
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			Recover Password
		</div>
		<div class="box-type-1"> 
			<div class="login-form">  
				<?php
					if(empty($errors)===false)
						echo output_errors($errors);
				?>
				<div class="login-form-inner"> 
					<header>Recover</header>
					<form method="post" action="">
						<input type="text" name="username" placeholder="Username*" />
						<input type="text" name="code" placeholder="Verification Code*" />
						<input type="submit" value="Submit" />
						<a href="../pages/recover_password.php"><input type="button" value="Resend Code" /></a>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>