<?php 
	include '../core/init.php'; 
	protect_page();
	
	$current='access';
	$done=0;

	include '../includes/header.php';
?>
<title>EACC : Batches</title>
<script type="text/javascript">
	function set_exam()
	{	var subject;
		subject=document.getElementById("subject").value;
		var res=subject.split(' ');
		if(inarray('Lab',res)==true||inarray('lab',res)==true)
		{	document.getElementById("exam").style.display="none";
			document.getElementById("lab_exam").style.display="block";
			document.getElementById("workshop").style.display="none";
			}
		else if(inarray('Workshop',res)==true||inarray('workshop',res)==true)
		{	document.getElementById("exam").style.display="none";
			document.getElementById("lab_exam").style.display="none";
			document.getElementById("workshop").style.display="block";
			}
		else
		{	document.getElementById("lab_exam").style.display="none";
			document.getElementById("exam").style.display="block";
			document.getElementById("workshop").style.display="none";
			}
		}
	function inarray(str,arr)
	{	for(var i=0;i<arr.length;i++)
			if(arr[i]==str)
				return true;
		return false;
		}
		
	 setTimeout(function () {
        var el = document.getElementById('alwaysFetch');
        el.value = el.value ? location.reload() : true;
    }, 0);
	window.onload=set_exam;
</script>
<?php
	include '../includes/top.php';
	
	if(isset($_GET['sem'])&&isset($_GET['branch'])&&isset($_GET['batch']))
	{	$sem=$_GET['sem'];
		$branch=$_GET['branch'];
		$batch=$_GET['batch'];
		}
		
	if(empty($_POST)===false)
	{	if(empty($_POST['subject'])&&(isset($_GET['subject'])===false))
			$errors[]='please select the subject to view result ...';
		if(empty($_POST['exam'])===true&&empty($_POST['lab_exam'])===true&&empty($_POST['workshop'])==true)
			$errors[]='please select the exam to view result ...';
		}
?>
<div class="content">
	<div class="intro">
		<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
	</div>
	<div class="container-type-2">
		<header>View Results</header>
		<?php
			$branch_subject=get_prev_subjects($batch,$sem,$branch);
			$branch_subject=explode(',',$branch_subject['course_name']);
		?>
		<div class="columns-type-3">
			<?php
				if(empty($_POST)===false&&empty($errors)===true)
				{	if(empty($_POST['exam'])&&empty($_POST['workshop']))
						$exam=$_POST['lab_exam'];
					else if(empty($_POST['lab_exam'])&&empty($_POST['workshop']))
						$exam=$_POST['exam'];
					else if(empty($_POST['exam'])&&empty($_POST['lab_exam']))
						$exam=$_POST['workshop'];
					
					$a=strtolower($_POST['subject']);
					if(in_array('lab',explode(' ',$a)))	
						header('Location:lab_result.php?batch='.$batch.'&sem='.$sem.'&branch='.$branch.'&exam='.$exam.'&subject='.$_POST['subject']);
					else if(in_array('workshop',explode(' ',$a)))
						header('Location:workshop_result.php?batch='.$batch.'&sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&exam='.$exam.'&subject='.$_POST['subject']);
					else
						header('Location:result.php?batch='.$batch.'&sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&exam='.$exam.'&subject='.$_POST['subject']);
					}
				else if(empty($errors)===false)
				{	?>
						<div id="errors">
							<?php echo output_errors($errors); ?>
						</div>
					<?php
					}
			?>
			<form action="" method="post">
				<div class="column1">	
					<?php	
						{	?>
								<select name="subject" id="subject" onchange="set_exam()">
									<option value="" class="list1">-- Select Subject* --</option>
									<?php
										foreach($branch_subject as $x)
										{	?>
												<option value="<?php echo $x; ?>" class="list1"><?php echo $x;?></option>
											<?php
											}
									?>
								</select>
							<?php
							}
					?>
				</div>
				<div class="column2">
					<?php   
						{	?>
								<select name="exam" id="exam">
									<option value="" class="list1">-- Marks for* --</option>
									<option value="minor1" class="list1">Minor 1</option>
									<option value="minor2" class="list1">Minor 2</option>
									<option value="major" class="list1">Major</option>
									<option value="ca" class="list1">Class Assessment</option>
									<option value="total" class="list1">All</option>
								</select>
								<select name="lab_exam" id="lab_exam">
									<option value="" class="list1">-- Marks for* --</option>
									<option value="accessment" class="list1">Accessment</option>
									<option value="attendance" class="list1">Attendance</option>
									<option value="viva" class="list1">Viva</option>
									<option value="lab" class="list1">Lab</option>
									<option value="total" class="list1">All</option>
								</select>
								<select name="workshop" id="workshop">
									<option value="" class="list1">-- Marks for* --</option>
									<option value="class work" class="list1">Class work</option>
									<option value="final term exam" class="list1">Final term exam</option>
									<option value="total" class="list1">All</option>
								</select><br/><br/>
							<?php
							}
					?>
				</div>
				<div class="container-type-2-submit">
					<input type="submit" value="Get Result">
				</div>
			</form>
		</div>
	</div>
</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>