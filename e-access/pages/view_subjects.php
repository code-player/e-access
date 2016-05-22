<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$i=0;
	$check=0;
	$current='access';
	$here='sem_subjects';
	include '../includes/header.php';
?>
<title>EACC : Subjects</title>
<?php
	include '../includes/top.php';
		
	$now_branch=array();
	$now_sem=$coord_sem;
	foreach($coord_branch as $p)
		$now_branch[]=$p;
	foreach($now_sem as $key=>$value)
		$now_branch[$key]=$now_branch[$key].' '.$now_sem[$key];
		
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Subjects For <?php echo implode(' , ',$now_branch);?> Semester/es</header>
			<div id="errors">
					NOTE : Click on course name to change the course details...
			</div>
			<div class="columns-type-2">
				<div class="rec-search">	
					<table class="result">
						<thead class="result-thead">	
							<tr>
								<th>S.no</th>
								<th>Semester(es)</th>
								<th>Branch(es)</th>
								<th>Course Name(s)</th>
								<th>Course Code(s)</th>
								<th>Credit(s)</th>
							</tr>
						</thead>
						<tbody class="results">	
							<?php
								foreach($coord_sem as $key=>$value)
								{	$i+=1;
									$result=get_subjects($coord_sem[$key],$coord_branch[$key]);
									$subject=explode(',',$result['course_name']);			
									$code=explode(',',$result['course_code']);				
									$credit=explode(',',$result['credits']);				
									
									asort($subject);
									
									foreach($credit as $k=>$val)
									{	if($credit[$k]!=='')
											$subject[$k]='<a href=\'sem_subject_details.php?sem='.$coord_sem[$key].'&branch='.$coord_branch[$key].'&subject='.$subject[$k].'\'>'.$subject[$k].'</a>';
										}
									
									$len=count($subject);
									?>
										<tr>
											<td rowspan="<?php echo $len;?>"><?php echo $i.'.';?></td>
											<td rowspan="<?php echo $len;?>"><?php echo $coord_sem[$key];?></td>
											<td rowspan="<?php echo $len;?>"><?php echo $coord_branch[$key];?></td>
											<?php
												$p=0;
												foreach($subject as $key=>$value)
												{	if($p!==0)
													{	?>	
															<tr>
														<?php
														}
													?>
														<td title="Click to change the subject"><?php echo $subject[$key];?></td>
														<td><?php echo $code[$key];?></td>
														<td><?php echo $credit[$key];?></td>
													<?php
													if($p!==0)
													{	?>	
															</tr>
														<?php
														}
													$p+=1;
													}
											?>
										</tr>
									<?php
									}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>