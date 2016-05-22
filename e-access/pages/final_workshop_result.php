<?php 
	include '../core/init.php';
	
	if(logged_in()===false)
		header('Location:../pages/index.php');
	$i=0;
	
	if(empty($_GET))
		header('Location:../pages/view.php'); 
	
	$sem=$_GET['sem'];
	$branch=$_GET['branch'];
	$subject=$_GET['subject'];
	$exam=$_GET['exam'];
	if(isset($_GET['batch'])&&empty($_GET['batch'])===false)
		$batch=$_GET['batch'];
	else
		$batch='';
	if($batch!=='')
	{	$result=get_result($batch,$sem,$branch,$subject,$exam);
		$branch_subject=get_prev_subjects($batch,$sem,$branch);
		
		$max=explode('-',$batch)[0];
		switch($sem)
		{	case 'III'	:	$date=$batch+1;
							break;
			case 'IV'	:	
			case 'V'	:	$date=$batch+2;
							break;
			case 'VI'	:	
			case 'VII'	:	$date=$batch+3;
							break;
			case 'VIII'	:	$date=$batch+4;
							break;
			}
		}	
	else
	{	$result=get_result($batch,$sem,$branch,$subject,$exam);
		$branch_subject=get_subjects($sem,$branch);
		
		$max=mysqli_query($GLOBALS['conn1'],'SELECT MAX(batch) FROM `sem_'.$sem.'_'.$branch.'` WHERE 1');
		$max=mysqli_fetch_array($max);
		$max=$max[0];
		}
		
	$course_name=explode(',',$branch_subject['course_name']);
	$course_code=explode(',',$branch_subject['course_code']);
	$credit=explode(',',$branch_subject['credits']);
	
	foreach($course_name as $key=>$value)
	{	if($value===$subject)
			break;
		}
		
?>
<!Doctype html>
<html>  
<head>
	<link rel="stylesheet" href="../css/result.css" />
	<link rel="shortcut icon" href="../images/fav.png">
	<?php	
		if($batch!=='')
		{	?>
				<title>EACC : Batches</title>
			<?php
			}
		else
		{	?>
				<title>EACC : Results</title>
			<?php
			}
	?>
</head>
<body>
	<div class="get_result">
		Department of <?php echo $branch;?><br/>
		<b>National Institute of Technology Srinagar</b><br/>
		Award Roll<br/>
		<?php
			if($batch!=='')
			{	echo 'Batch : '.$batch.'<br/>';
				if($sem%2==0)
					echo 'Spring '.$date;
				else
					echo 'Autumn '.$date;
				}
			else
			{	if($sem%2==0)
					echo 'Spring '.date('Y');
				else
					echo 'Autumn '.date('Y');
				}		
		?>
	</div><br/>
	<span class="get_result1">
		<b>Branch :</b> <?php echo $branch;?>
		<br/>
		<b>Course :</b> <?php echo $course_name[$key];?>
		<br/>
		<b>Credits :</b> <?php echo $credit[$key];?>
	</span>
	<span class="get_result2">
		<b>Tab A/Tab B/Office Copy</b>
		<br/>
		<b>Semester :</b> <?php echo $sem;?>
		<br/>
		<b>Course Code :</b> <?php echo $course_code[$key];?>
	</span>
	<br/><br/><br/>
	<table class="result">
		<thead class="result-thead">
			<tr>
				<th>S.no.</th>
				<th>Enroll. no.</th>
				<?php 
					if($exam==='total'||$exam==='class work')
					{	?>
							<th>Class work<br/>(max=60)</th>
						<?php
						}
					if($exam==='total'||$exam==='final term exam')
					{	?>
							<th>Attendance<br/>(max=40)</th>
						<?php
						}	
					if($exam==='total')
					{	?>
							<th>Total<br/>(max=100)</th>
							<th>Grade</th>
						<?php
						}
				?>
			</tr>
		</thead>
		<tbody class="results">
		<?php
			$test=0;
			while($x=mysqli_fetch_assoc($result))
			{	if($x['batch']!==$max&&$test===0)
				{	?>
						<tr>
							<td colspan='100%' style="text-align:left"><br/>Re-Registration</td>
						</tr>
					<?php
					$test=1;
					$i=0;
					}
				$i+=1;
				?>
					<tr>
						<td><?php echo $i;?></td>
						<td><?php echo $x['enroll'];?></td>
						<?php 
							if($exam==='total'||$exam==='class work')
							{	?>
									<td><?php echo $x['class work_'.$subject];?></td>
								<?php
								}
							if($exam==='total'||$exam==='final term exam')
							{	?>
									<td><?php echo $x['final term exam_'.$subject];?></td>
								<?php
								}	
							if($exam==='total')
							{	$tot=$x['class work_'.$subject]+$x['final term exam_'.$subject];
								?>
									<td><?php echo $tot;?></td>
									<td><?php echo grade($tot);?></td>
								<?php
								}
						?>
					</tr>
				<?php
				}
		?>
		</tbody>
	</table>
			
</body>
</html>