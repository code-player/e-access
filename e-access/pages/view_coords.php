<?php
    include '../core/init.php';
	protect_page();
	
	if(preg_match("/admin/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$i=0;
	$current='access';
	$here='sem_coords';
	include '../includes/header.php';
?>
<title>EACC : Coordinators</title>
<?php
	include '../includes/top.php';
	
	$result=check_desc('coord_');
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Semester Coordinators For Different Semesters/Branches</header>
			<div id="errors">
				NOTE : Click on Department to change the semester coordinator for that semester...
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
								<th>Semester</th>
								<th>Branch(es)</th>
							</tr>
						</thead>
						<tbody class="results">
							<?php
								while($x=mysqli_fetch_assoc($result))
								{	$i+=1;
									$value=array();
									$branch=array();
									$sem=array();
									foreach(explode(',',$x['user_desc']) as $p)
										if(preg_match("/coord_/",$p)==true)
											$value[]=$p;
									foreach($value as $p)
									{	$p=substr($p,6);
										$b=substr($p,0,strpos($p,'_'));
										$val=substr($p,strlen($b)+1);
										if(empty(substr($val,0,strpos($val,'_')))===false)
											$sem[]=substr($val,0,strpos($val,'_'));
										if($b==='sem')
											$branch[]='<a href=\'sem_coords_details.php?branch='.$b.'&sem='.substr($val,0,strpos($val,'_')).'&name='.$x['first_name'].' '.$x['last_name'].'\'>All</a>';
										else 
											$branch[]='<a href=\'sem_coords_details.php?branch='.$b.'&sem='.substr($val,0,strpos($val,'_')).'&name='.$x['first_name'].' '.$x['last_name'].'\'>'.$b.'</a>';
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
														<td title="Click to change semester coordinator for this branch/semester"><?php echo $branch[$key];?></td>
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