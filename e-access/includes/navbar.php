<div class="navbar">
	<ul>
		<?php
		if(logged_in() === false)
		{
			?>
				<li><a <?php if($current==='home'){echo 'class="current"';}?> href="../pages/index.php">Home</a></li>
			<?php
			}
		?>
		<?php
		if(logged_in() === true)
		{
			?>
				<li><a <?php if($current==='access'){echo 'class="current"';}?> href="../pages/access.php">Home</a></li>
			<?php
			}
		?>	
		<?php 
		if(logged_in() === false)
		{
			?>
				<li><a <?php if($current==='login'){echo 'class="current"';}?> href="../pages/login.php">Login</a></li>
			<?php
			}
		?>
		<?php 
		if(logged_in() === false)
		{
			?>
				<li><a <?php if($current==='register'){echo 'class="current"';}?> href="../pages/register.php">Register</a></li>
			<?php
			}
		?>	
				<li><a <?php if($current==='contacts'){echo 'class="current"';}?> href="../pages/contacts.php">Contacts</a></li>
				<li><a <?php if($current==='help'){echo 'class="current"';}?> href="../pages/help.php">HelpDesk</a></li>
	</ul>
</div>
<div class="fixed-logo">E-Access</div>
	<?php
	if(logged_in() === true)
	{
		?>
			<div class="fixed-logout"><a href="../pages/logout.php">Logout !</a></div>
		<?php
		}
	?>