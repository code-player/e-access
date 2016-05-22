<?php 
	include '../core/init.php'; 
	protect_page();
	$current='access';
	$here='view';
	include '../includes/header.php';
?>
<title>EACC : Workline</title>
<?php
	include '../includes/top.php';
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<?php 
			$files=glob('../*.sql');
			if(empty($files)===false)
				foreach($files as $x)
					unlink($x);
			
			if(isset($_GET['done'])===true&&empty($_GET['done'])===true)
				if(in_array('admin',explode(',',$user_data['user_desc'])))
				{	?>
						<div id="alert">
							<?php	
								echo '<br/>admin rights has been successfully transferred to another account...<br/>';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="Close">
							</div><br/>
						</div>
					<?php
					}
			if(isset($_GET['user'])===true&&$_GET['user']===$user_data['username'])
			{	?>
					<div id="alert">
						<?php	
							echo '<br/>Your username is : <em>'.$_GET['user'].'</em> <br/><br/>To change your account details  
								  <em><a href="../pages/update.php">CLICK HERE</a></em><br/>';
							
						?>
						<div class="buts">
							<input type="button" onclick="location.href='../pages/view.php'" value="Close">
						</div><br/>
					</div>
				<?php
				}
		?>	
			<nav>
				<?php   
					if(preg_match("/admin/",$user_data['user_desc'])==true)
					{	?>
							<div class="menu-item">
								<h4><a href=" ">Inactive Semesters' Records</a></h4>
								<ul>
									<li><a href="../pages/prev_enrolled.php">Enrolled Students</a></li>
									<li><a href="../pages/prev_result.php">View Result</a></li>
									<li><a href="../pages/upload_result.php">Import result to server</a></li>
									<li><a href="../pages/move_result.php">Export result from server</a></li>
									<br/>
								</ul>
							</div>
						<?php
						}
				?>
				<div class="menu-item">
				  <h4><a href="">Students' Records</a></h4>
				  <ul>
					<li><a href="../pages/view_students.php">View Enrolled Students</a></li>
					<?php
						if(preg_match("/coord_/",$user_data['user_desc'])==true)
						{	?>
								<li><a href="../pages/create_new.php">Enroll New Student</a></li>
							<?php
							}
					?>
					<br/>
				 </ul>
				</div>
				   
				<div class="menu-item">
				  <h4><a href="">Result Management</a></h4>
				  <ul>
					<?php   
						if(preg_match("/teach_/",$user_data['user_desc'])==true)
						{	?>
								<li><a href="../pages/create_result.php">Edit Result</a></li>
							<?php
							}
					?>
					<li><a href="../pages/view_result.php">View Result</a></li>
					<?php   
						if(preg_match("/coord_/",$user_data['user_desc'])==true)
						{	?>
								<li><a href="../pages/create_backup.php">Create Result Backup</a></li>
								<li><a href="../pages/clear.php">Clear All Result</a></li>
							<?php
							}
					?>
					<br/>
				  </ul>
				</div>
				<?php   
					if(preg_match("/coord_/",$user_data['user_desc'])==true)
					{	?>
							<div class="menu-item">
								<h4><a href=" ">Semester Coordinator Rights</a></h4>
								<ul>
									<li><a href="../pages/set_subjects.php">Set Semester Subjects</a></li>
									<li><a href="../pages/view_subjects.php">View Semester Subjects</a></li>
									<li><a href="../pages/set_teachers.php">Set Semester Teachers</a></li>
									<li><a href="../pages/view_teachers.php">View Semester Teachers</a></li>
									<br/>
								</ul>
							</div>
						<?php
						}
				?>
				<?php   
					if(preg_match("/admin/",$user_data['user_desc'])==true)
					{	?>
							<div class="menu-item">
								<h4><a href=" ">Administrator Rights</a></h4>
								<ul>
									<li><a href="../pages/set_coords.php">Set Semester Coordinators</a></li>
									<li><a href="../pages/view_coords.php">View Semester Coordinators</a></li>
									<br/>
								</ul>
							</div>
						<?php
						}
				?>
				<div class="menu-item">
				  <h4><a href=" ">My Profile</a></h4>
				  <ul>
					<li><a href="../pages/change_password.php">Change Password</a></li>
					<li><a href="../pages/update.php">Update Details</a></li>
					<?php   
						if(preg_match("/admin/",$user_data['user_desc'])==true)
						{	?>
								<li><a href="../pages/change_admin.php">Change Admin</a></li>
							<?php
							}
					?>
					<br/>
				  </ul>
				</div>
			</nav>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>