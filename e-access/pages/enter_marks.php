<?php 
	include '../core/init.php'; 
	protect_page();
	$current='access';
	$here='result';
	include '../includes/header.php';
?>
<title>EACC : Results</title>
<?php
	include '../includes/top.php';
		
	GLOBAL $sem,$branch,$exam,$subject;
	
	if(isset($_GET['success'])===false||empty($_GET['success'])===false)
	{	$sem=$_GET['sem'];
		$branch=$_GET['branch'];
		$exam=$_GET['exam'];
		$subject=$_GET['subject'];
		
		switch($exam)
		{	case 'minor1'			:
			case 'minor2'			:	$max=20;	break;
			case 'major'			:	$max=50;	break;
			case 'ca'				:	$max=10;	break;
			case 'accessment'		:	$max=40;	break;
			case 'attendance'		: 	$max=10;	break;
			case 'viva'				:	$max=30;	break;
			case 'lab'				: 	$max=20;	break;
			case 'class work'		:   $max=60;	break;
			case 'final term exam'	:	$max=40;	break;
			}
		}

	if(empty($_POST)===false)
	{	$data=array();
		foreach($_POST as $key=>$value)
		{	if($value==="")
			{	$errors[]='please fill the marks for all students correctly...';
				break 1;
				}
			$data[$key]=$value;
			}
		}
?>
<div class="content">
	<div class="intro"> 
		<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?> 
	</div>
	<div class="container-type-2">
		<header>Edit/Add Marks</header>
		<?php
			$query=marks_form($sem,$branch,$subject,$exam);
			if(isset($_GET['success'])&&empty($_GET['success']))
			{	?>
					<div id="alert">
						<?php echo '<br/>Student\'s marks has been added successfully......'; ?>
						<div class="buts">
							<input type="button" onclick="location.href='../pages/view.php'" value="OK">
						</div><br/>
					</div>
				<?php
				}
			if(empty($_POST)===false&&empty($errors)===true)
			{	add_marks($data,$branch,$exam,$sem,$subject);
				header('Location:enter_marks.php?success'); 
				exit();
				}
			else if(empty($errors)===false)
			{	?>
					<div id="errors">
						<?php	echo output_errors($errors);?>
					</div>
				<?php
				}
		?>
		<div class="columns-type-2">					
			<div class="rec-search">
				<form action="" method="post">
					<table>
						<caption>Result for <?php echo $branch.' '.$sem.' semester for '.$subject; ?>!</caption>
						<thead class="result-thead">	
							<tr>
								<th>S.no</th>
								<th>Enroll.no.</th>
								<th>Marks(max=<?php echo $max;?>)</th>
								<th class="left-separation">S.no</th>
								<th>Enroll.no.</th>
								<th>Marks(max=<?php echo $max;?>)</th>
							</tr>
						</thead>
						<tbody class="results">	
							<?php
								$i=0;
								while($row=mysqli_fetch_assoc($query))
								{	$i+=1;
									if($i%2==1)
									{	?>
											<tr>
												<td><?php echo $i.'.';?></td>
												<td><?php echo $row['enroll'];?></td>
												<td><input type="number" step="any" min="0" max="<?php echo $max; ?>" name="<?php echo $row['enroll'];?>" value="<?php echo $row[$exam.'_'.$subject];?>"></td>
										<?php
										}
									else
									{	?>
												<td class="left-separation"><?php echo $i.'.';?></td>
												<td><?php echo $row['enroll'];?></td>
												<td><input type="number" step="any" min="0" max="<?php echo $max; ?>" name="<?php echo $row['enroll'];?>" value="<?php echo $row[$exam.'_'.$subject];?>"></td>
											</tr>
										<?php
										}
									}
							?>
						</tbody>
					</table><br/><br/>
					<div class="container-type-2-submit">
						<input type="reset" value="Reset all"><br/><br/>
						<input type="submit" value="Add record">
					</div>
				</form>
			</div>
			<div id="errors">
				<?php echo 'Note : form contains the present marks of the students if any filled earlier...';?>
			</div>
		</div>
	</div>
</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>