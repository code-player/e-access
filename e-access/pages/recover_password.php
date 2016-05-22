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
		
		if(empty($errors)===true)
		{	$username=sanitize($_POST['username']);
			$result=mysqli_query($GLOBALS['conn'],"SELECT email,first_name FROM users WHERE username='$username'");
			if(mysqli_num_rows($result)==1)	
			{	$result=mysqli_fetch_assoc($result);
				$code=recover_password($result['email'],$username,$result['first_name']);			
				header('Location: ../pages/recover_password.php?success');
				exit();
				}
			else
				$errors[]='Sorry , we\'re unable to trace your username...<br/>Please try again later...';
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
					if(isset($_GET['success'])===true&&empty($_GET['success'])===true)
					{	?>
							<article id="About">	
								<header>
									<div class="About-inside">
										<h2>	
											<?php	
												echo '<br/>Verification Code has been sent to your email...<br/><br/>
														Please check your email to proceed...';
											?>
										</h2>
									</div>
								</header>
							</article>
						<?php
						}
					else
					{	if(empty($errors)===false)
						{	echo output_errors($errors);
							}
						?>
							<div class="login-form-inner"> 
								<header>Recover</header>
								<div style="margin-left:5%">
									A one-time verification code will be send to your registered email ... 
								</div>
								<form method="post" action="">
									<input type="text" name="username" placeholder="Username*" />
									<input type="submit" value="Send verification Code" /><br/><br/><br/>
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