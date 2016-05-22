<?php
	protect_page();
	GLOBAL $here;
	
	if($here==='result')
	{	if(empty($teach_sem[1])===true)
		{	$sem='sem_'.$teach_sem[0].'_'.$teach_branch[0];
			$year='sem_'.$teach_sem[0];
			$query="INSERT INTO result.`$sem`(enroll,batch) SELECT enroll,batch FROM e_access.$year WHERE branch='$teach_branch[0]' AND sem='$teach_sem[0]' AND enroll NOT IN(SELECT enroll FROM result.`$sem`) ORDER BY batch DESC ,enroll";
			}
		mysqli_query($GLOBALS['conn'],$query);
		header('Location:../pages/subject.php?sem='.$teach_sem[0].'&branch='.$teach_branch[0].'&here='.$here.'&subject='.$teach_subject[0]);
		}
			
	if(empty($_POST)===false)
	{	if(empty($_POST['sem'])===true)
			$errors[]='please select a semester from the list below...';
		if(empty($_POST['branch'])===true)
			$errors[]='Please select the branch to set result for...';
		}
	
	if(empty($_POST)===false&&empty($errors)===true)
	{	$list=$_POST['sem'];								
		$branch=$_POST['branch'];							
		$sem='sem_'.$_POST['sem'].'_'.$branch;
		$year='sem_'.$_POST['sem'];
			
		$query="INSERT INTO result.`$sem`(enroll,batch) SELECT enroll,batch FROM e_access.$year WHERE branch='$branch' AND sem='$list' AND enroll NOT IN(SELECT enroll FROM result.`$sem`) ORDER BY batch DESC,enroll";
		
		mysqli_query($GLOBALS['conn'],$query);
		header('Location:../pages/subject.php?sem='.$_POST['sem'].'&branch='.$branch.'&here='.$here);
		}
	else
	{	?>
			<div class="columns-type-3">
				<?php
					if(empty($errors)===false)
					{	?>
						<div id="errors">
							<?php echo output_errors($errors);?>
						</div>
						<?php
						}
				?>		
				<form method="post" action="">
					<div class="column1">
						<?php
							if($here==='result')
							{	?>
									<select name="sem" id="sem" onclick="check_sem()">
								<?php
								}
							else
							{	?>
									<select name="sem" id="sem">
								<?php
								}
						?>
							<option value="" class="list1">-- Select Semester* --</option>
							<?php
								if($here==='result')
								{	$sem=array_unique($teach_sem);
									foreach($sem as $x)
									{	?>
											<option value="<?php echo $x;?>" class="list1"><?php echo 'SEMESTER '.$x;?></option>
										<?php
										}
									}
								else
								{	?>	
										<option value="III" class="list1">SEMESTER III</option>
										<option value="IV" class="list1">SEMESTER IV</option>
										<option value="V" class="list1">SEMESTER V</option>
										<option value="VI" class="list1">SEMESTER VI</option>
										<option value="VII" class="list1">SEMESTER VII</option>
										<option value="VIII" class="list1">SEMESTER VIII</option>
									<?php
									}
							?>
						</select>
					</div>	
					<div class="column2">
						<select name="branch" id="branch">
							<option value="" class="list1">-- Select Branch* --</option>
							<?php
								if($here==='result')
								{	$branch=array_unique($teach_branch);
									foreach($branch as $x)
									{	?>
											<option value="<?php echo $x;?>" class="list1"><?php echo $x;?></option>
										<?php
										}
									}
								else
								{	?>
										<option value="Information Technology" class="list1">Information Technology</option>
										<option value="Computer Science" class="list1">Computer Science</option>
										<option value="Electronics And Communication" class="list1">Electronics And Communication</option>
										<option value="Mechanical Engineering" class="list1">Mechanical Engineering</option>
										<option value="Metallurgy Engineering" class="list1">Metallurgy Engineering</option>
										<option value="Electrical Engineering" class="list1">Electrical Engineering</option>
										<option value="Civil Engineering" class="list1">Civil Engineering</option>
										<option value="Chemical Engineering" class="list1">Chemical Engineering</option>
									<?php
									}	
							?>
						</select><br/><br/>
					</div>
					<div class="container-type-2-submit">
						<input type="submit" value="Next">
					</div>
				</form>
			</div>
		<?php
		}
?>