<?php 
	include '../core/init.php'; 
	logged_in_redirect();
	$current='home';
	include '../includes/header.php';
?>
<title>EACC : Welcome!</title>
<?php
	include '../includes/top.php';
?>
	<div class="content2">
		<div class="intro"> 
		 NIT-SRI E-Access System  
		</div>
		<article id="About">
			<header>
				<div class="About-inside"><br>
					<p>This is an E-Register facility where Faculty members currently working in NITSRI can store/ access the information of Students enrolled in the college and other important details about them. <br>
				<div class="Get_started"><a href="login.php">Get Access</a></div>
				<br><br>
				<div class="General">You can also visit the college Website <a href="http://nitsri.net">here</a></div>
					</p>
				</div>
			</header>
		</article>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>
