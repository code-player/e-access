<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/coord_/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$i=0;
	$check=0;
	$current='access';
	$here='sem_teachers';
	include '../includes/header.php';
?>
<title>EACC : Teachers</title>
<?php
	include '../includes/top.php';
	
	$now_branch=array();
	$now_sem=$coord_sem;
	foreach($coord_branch as $p)
		$now_branch[]=$p;
	$val=array();
	foreach($coord_sem as $k=>$value)
	{	$val[]='teach_'.$coord_branch[$k].'_'.$coord_sem[$k].'_';
		$now_branch[$k]=$now_branch[$k].' '.$now_sem[$k];
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Teachers For <?php echo implode(' , ',$now_branch);?> Semester/es</header>
			<div id="errors">
				NOTE : Click on Subject name to change the teacher for that Subject...
			</div>
			<div class="columns-type-2">
				<div class="rec-search">	
					<table class="result">
						<thead class="result-thead">
							<tr>
								<th>S.no</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Department</th>
								<th>Semester(es)</th>
								<th>Branch(es)</th>
								<th>Subject(es)</th>
							</tr>
						</thead>
						<tbody class="results">	
							<?php
								$user=array();
								foreach($val as $m)
								{	$result=check_desc($m);
									if(mysqli_num_rows($result)>0)
									{	while($x=mysqli_fetch_assoc($result))
										{	if(in_array($x['user_id'],$user)===false)
											{	$user[]=$x['user_id'];
												$i+=1;
												$value=array();
												$branch=array();
												$sem=array();
												$subject=array();
												foreach(explode(',',$x['user_desc']) as $p)
													if(preg_match("/teach_/",$p)==true)
														$value[]=$p;
												foreach($value as $p)
												{	$p=substr($p,6);
													$b=substr($p,0,strpos($p,'_'));
													$val=substr($p,strlen($b)+1);
													$s=substr($val,0,strpos($val,'_'));
													$val=substr($val,strlen($s)+1);
													if(in_array($s,$coord_sem)===true)
													{	$sem[]=$s;
														$branch[]=$b;
														if(empty(substr($val,0,strpos($val,'_')))===false)
															$subject[]='<a href=\'sem_teach_details.php?branch='.$b.'&sem='.$s.'&subject='.substr($val,0,strpos($val,'_')).'\'>'.substr($val,0,strpos($val,'_')).'</a>';
														}
													}
												$len=count($branch);
												?>
													<tr>
														<td rowspan="<?php echo $len;?>"><?php echo $i.'.';?></td>
														<td rowspan="<?php echo $len;?>"><?php echo $x['first_name'];?></td>
														<td rowspan="<?php echo $len;?>"><?php echo $x['last_name'];?></td>
														<td rowspan="<?php echo $len;?>"><?php echo $x['department'];?></td>
														<?php
															$p=0;
															foreach($branch as $key=>$value)
															{	if($p!==0)
																{	?>	
																		<tr>
																	<?php
																	}
																?>
																	<td><?php echo $sem[$key];?></td>
																	<td><?php echo $branch[$key];?></td>
																	<td title="Click to change teacher for the subject"><?php echo $subject[$key];?></td>
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
											}
										}
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