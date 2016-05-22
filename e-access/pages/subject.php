<?php 
	include '../core/init.php'; 
	protect_page();
	
	if($_GET['here']==='result')
		if(preg_match("/teach_/",$user_data['user_desc'])==false)
			header('Location:access.php');
	$current='access';
	$done=0;

	include '../includes/header.php';
?>
<title>EACC : Results</title>
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
	window.onload=set_exam;
</script>
<?php
	include '../includes/top.php';
	if(isset($_GET['sem'])&&isset($_GET['branch'])&&isset($_GET['here']))
	{	$sem=$_GET['sem'];
		$branch=$_GET['branch'];
		$here=$_GET['here'];
		}
		
	if(empty($_POST)===false)
	{	if(empty($_POST['subject'])&&(isset($_GET['subject'])===false))
		{	if($here==='result')
				$errors[]='please select the subject to set result for...';
			else
				$errors[]='please select the subject to view result ...';
			}
		if(empty($_POST['exam'])===true&&empty($_POST['lab_exam'])===true&&empty($_POST['workshop'])==true)
		{	if($here==='result')
				$errors[]='please select the exam to set result for ...';
			else
				$errors[]='please select the exam to view result ...';
			}
		}
?>
<div class="content">
	<div class="intro">
		<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
	</div>
	<div class="container-type-2">
		
		<?php
			if($here==='result')
			{	?>
					<header>Create Result</header>
				<?php
				}
			else
			{	?>
					<header>View Results</header>
				<?php
				}
			if($here==='result')
			{	$val='teach_'.$branch.'_'.$sem.'_';
				if(preg_match("/$val/",$user_data['user_desc'])==false)
				{	?>
						<div id="errors">
							<?php echo 'You don\'t have permission for this branch/semester combination...<br/>'; ?>
						</div>	
					<?php
					$branch_subject=array();
					}
				else
				{	$done=1;
					$branch_subject=array();
											
					foreach($teach_sem as $key=>$value)
					{	if($value===$sem)
							$branch_subject[]=$teach_subject[$key];
						}
					}
				}
			else
			{	$branch_subject=get_subjects($sem,$branch);
				$branch_subject=explode(',',$branch_subject['course_name']);
				}
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
					if($here==='result')
					{	header('Location:enter_marks.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&exam='.$exam.'&subject='.$_POST['subject']);
						}
					else if($here==='view_result')
					{	$a=strtolower($_POST['subject']);
						if(in_array('lab',explode(' ',$a)))	
							header('Location:lab_result.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&exam='.$exam.'&subject='.$_POST['subject']);
						else if(in_array('workshop',explode(' ',$a)))
							header('Location:workshop_result.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&exam='.$exam.'&subject='.$_POST['subject']);
						else
							header('Location:result.php?sem='.$_GET['sem'].'&branch='.$_GET['branch'].'&exam='.$exam.'&subject='.$_POST['subject']);
						}
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
						if(($here==='result' && $done===1))
						{	foreach($branch_subject as $x)
							{	if(in_array('teach_'.$branch.'_'.$sem.'_'.$x.'_',explode(',',$user_data['user_desc']))===true)
								{	?>	
										<input type="text" id="subject" name="subject" value="<?php echo $x;?>" readonly>
									<?php
									}
								}
							}
						else
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
						if($here!=='clear')
						{	?>
								<select name="exam" id="exam">
									<option value="" class="list1">-- Marks for* --</option>
									<option value="minor1" class="list1">Minor 1</option>
									<option value="minor2" class="list1">Minor 2</option>
									<option value="major" class="list1">Major</option>
									<option value="ca" class="list1">Class Assessment</option>
									<?php	
										if($here==='view_result')
										{	?>
												<option value="total" class="list1">All</option>
											<?php
											}
									?>
								</select>
								<select name="lab_exam" id="lab_exam">
									<option value="" class="list1">-- Marks for* --</option>
									<option value="accessment" class="list1">Accessment</option>
									<option value="attendance" class="list1">Attendance</option>
									<option value="viva" class="list1">Viva</option>
									<option value="lab" class="list1">Lab</option>
									<?php	
										if($here==='view_result')
										{	?>
												<option value="total" class="list1">All</option>
											<?php
											}
									?>
								</select>
								<select name="workshop" id="workshop">
									<option value="" class="list1">-- Marks for* --</option>
									<option value="class work" class="list1">Class work</option>
									<option value="final term exam" class="list1">Final term exam</option>
								<?php	
									if($here==='view_result')
									{	?>
											<option value="total" class="list1">All</option>
										<?php
										}
								?>
								</select><br/><br/>
							<?php
							}
					?>
				</div>
				<div class="container-type-2-submit">
					<input type="submit" value="<?php if($here==='view_result')echo 'Get Result'; else echo 'Enter Marks'; ?>">
				</div>
			</form>
		</div>
	</div>
</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>