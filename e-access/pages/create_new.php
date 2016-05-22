<?php 
	include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:../pages/access.php');
	$current='access';
	$here='create';
	include '../includes/header.php';
?>
<title>EACC : e-Register</title>
<script type="text/javascript">
	function set_sem()
	{	var batch,sem;
		var time=new Date()
		var month=time.getMonth()+1
		var year=time.getFullYear()
		batch=document.getElementById("sbatch").value;
		var diff=year-batch;
		if(diff>=0&&diff<5)
		{	if(month<7)
				sem=2*diff;
			else
				sem=2*diff+1;
			if(sem>8||sem<3)
				sem=2;
			}
		document.getElementById("ssemester").selectedIndex=sem-2;
		}
</script>
<?php
	include '../includes/top.php';
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Enroll New Student</header>
			<?php
				if(isset($_POST['student_entry'])===false)	
					include '../includes/search.php';
				GLOBAL $check;
				if($check!==1&&isset($_POST['search'])===false)
					include '../includes/student_entry.php';
			?>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>