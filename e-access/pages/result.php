<?php 
	include '../core/init.php'; 
	protect_page();
	$current='access';
	$here='view_result';
	include '../includes/header.php';

	include '../includes/top.php';
	$i=0;
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
		?>
			<title>EACC : Batches</title>
		<?php
		}	
	else
	{	$result=get_result($batch,$sem,$branch,$subject,$exam);
		$branch_subject=get_subjects($sem,$branch);
		
		$max=mysqli_query($GLOBALS['conn1'],'SELECT MAX(batch) FROM `sem_'.$sem.'_'.$branch.'` WHERE 1');
		$max=mysqli_fetch_array($max);
		$max=$max[0];
		?>
			<title>EACC : Results</title>
		<?php
		}
	
	$course_name=explode(',',$branch_subject['course_name']);
	$course_code=explode(',',$branch_subject['course_code']);
	$credit=explode(',',$branch_subject['credits']);
	
	foreach($course_name as $key=>$value)
		if($value===$subject)
			break;
?>

	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<center><header>Department of <?php echo $branch;?><br/></header></center>
			<div class="get_result">
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
			</div>
			<span class="get_result1">
				Branch : <?php echo $branch;?>
				<br/>
				Course : <?php echo $course_name[$key];?>
				<br/>
				Credits : <?php echo $credit[$key];?>
			</span>
			<span class="get_result2">
				<?php
					if($exam!=='total')
					{	?>
							Exam : <?php echo $exam;?>
						<?php
						}
				?>
				<br/>
				Semester : <?php echo $sem;?>
				<br/>
				Course Code : <?php echo $course_code[$key];?>
			</span><br/>
			<div class="columns-type-2">
				<div class="rec-search">
					<table class="result">
						<thead class="result-thead">
							<tr>
								<th>S.no.</th>
								<th>Enroll. no.</th>
								<?php 
									if($exam==='total'||$exam==='minor1')
									{	?>
											<th>Minor 1<br/>(max=20)</th>
										<?php
										}
									if($exam==='total'||$exam==='minor2')
									{	?>
											<th>Minor 2<br/>(max=20)</th>
										<?php
										}	
									if($exam==='total'||$exam==='ca')
									{	?>
											<th>CA<br/>(max=10)</th>
										<?php
										}
									if($exam==='total'||$exam==='major')
									{	?>
											<th>Major<br/>(max=50)</th>
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
											</tbody>
													</table>
														<div style="color:#191970;border-bottom:1pt solid #708090;">
															<br/>Re - Registration
														</div>
													<table class="result">
											<tbody class="result">
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
												if($exam==='total'||$exam==='minor1')
												{	?>
														<td><?php echo $x['minor1_'.$subject];?></td>
													<?php
													}
												if($exam==='total'||$exam==='minor2')
												{	?>
														<td><?php echo $x['minor2_'.$subject];?></td>
													<?php
													}	
												if($exam==='total'||$exam==='major')
												{	?>
														<td><?php echo $x['major_'.$subject];?></td>
													<?php
													}
												if($exam==='total'||$exam==='ca')
												{	?>
														<td><?php echo $x['ca_'.$subject];?></td>
													<?php
													}
												
												if($exam==='total')
												{	$tot=$x['minor1_'.$subject]+$x['minor2_'.$subject]+$x['major_'.$subject]+$x['ca_'.$subject];
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
				</div>
				<?php
					if($batch!=='')
						$url='../pages/final_result.php?batch='.$batch.'&sem='.$sem.'&branch='.$branch.'&exam='.$exam.'&subject='.$subject;
					else
						$url='../pages/final_result.php?sem='.$sem.'&branch='.$branch.'&exam='.$exam.'&subject='.$subject;
				?>
				<div class="buts">
					<a href="<?php echo $url;?>" target="_blank"><input type="button" value="Print / Save"/></a>
				</div>
			</div>
		</div>
	</div>
	
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>