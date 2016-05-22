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
	
	if(empty($_POST)===false)
	{	$required_fields = array('password','re_password');
		foreach($_POST as $key=>$value)
		{	if(empty($value)&&in_array($key,$required_fields)===true)
			{	$errors[]='Please enter your current password...';
				break 1;
				}
			}
		if(empty($errors)===true)
		{	if($_POST['password']!==$_POST['re_password'])
				$errors[]='your passwords do not match...';
			else if(md5($_POST['password'])!==$user_data['password'])
				$errors[]='your have entered an incorrect password...';
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Change Admin</header>
			<div class="columns-type-3">
				<?php
				if(isset($_GET['success'])===false||empty($_GET['success'])===false)
				{	if(empty($_POST)===false && empty($errors)===true)
					{	header('Location:enter_admin_details.php?user='.$user_data['first_name']);
						}
					if(empty($errors)===false)
					{	?>
							<div id="errors">
								<?php	echo output_errors($errors); ?>
							</div>
						<?php
						}
						?>			
							<form action="" method="post">
								<div class="column1">	
									<p>Enter password*:</p>
									<p>Re enter password*:</p>
								</div>
								<div class="column2">
									<input type="password" name="password" placeholder="Password">
									<input type="password" name="re_password" placeholder="Confirm Password"><br/><br/>
								</div>
								<div class="container-type-2-submit">
									<input type="submit" value="Continue">
								</div>
							</form>
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
