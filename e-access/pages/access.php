<?php 
	include '../core/init.php'; 
	logged_in_redirect();
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
		 WorkLine ! 
		</div>
		<article id="About">
			<header>
				<div class="About-inside"><br>
					<p>You are not allowed to view the contents of this page...
						<br/><br/>
						<div class="General">
							<h2 style="text-align:center">You need to <a href='../pages/login.php'>log in</a> to access this page</h2>
						</div>
					</p>
				</div>
			</header>
		</article>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>