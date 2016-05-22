<?php 
	include '../core/init.php'; 
	protect_page();
	$current='access';
	$here='account';
	
	include '../includes/header.php';
?>
<title>EACC : Profile</title>
<?php
	include '../includes/top.php';
	
	if(empty($_POST)===false)
	{	$required_fields = array('current_password','password','re_password');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Fields marked with * are required';
				break 1;
				}
			}
		if(empty($errors)===true)
		{	if(md5($_POST['current_password'])===$user_data['password'])
			{	if(trim($_POST['password'])!==trim($_POST['re_password']))	
					$errors[]='your new passwords do not match.';
				else if(strlen($_POST['password'])<6)
					$errors[]='your password must be atleast 6 characters.';
				else if(trim($_POST['password'])===$_POST['current_password'])
					$errors[]='New password must be different from current password.';
				}
			else
				$errors[]='your current password is incorrect...';
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Change Password</header>
			<?php
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php echo '<br/>Your passsword has been changed successfully...'; ?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
			?>
			<div class="columns-type-3">
				<?php
					if(empty($_POST)===false && empty($errors)===true)
					{	change_password($user_data['user_id'],$_POST['password']);
						header('Location:change_password.php?success');
						}
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
						<p>Current password* :</p>
						<p>New password* :</p>
						<p>Re enter New password* :</p>
					</div>
					<div class="column2">
						<input type="password" name="current_password" placeholder="Current Password">
						<input type="password" name="password" placeholder="New Password">
						<input type="password" name="re_password" placeholder="Confirm New Password">
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Change Password">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>