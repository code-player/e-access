<?php
	protect_page();
	
	if(empty($_POST)===false)
	{	if(empty($_POST['batch'])===true)
			$errors[]='please select the batch from the list below...';
		if(empty($_POST['sem'])===true)
			$errors[]='please select a semester from the list below...';
		if(empty($_POST['branch'])===true)
			$errors[]='Please select the branch to set result for...';
		}
	
	
	$query='SELECT DISTINCT `TABLE_SCHEMA` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` LIKE \'____-____%\'';
	$batch=mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],$query));
			
	if(empty($_POST)===false&&empty($errors)===true)
	{	header('Location:../pages/prev_subject.php?batch='.$_POST['batch'].'&sem='.$_POST['sem'].'&branch='.$_POST['branch']);
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
						<select name="batch">
							<option value="" class="list1">--Select Batch*--</option>
							<?php
								foreach($batch as $x)
								{	?>
										<option value="<?php echo $x;?>" class="list1"><?php echo $x;?></option>
									<?php
									}
							?>
						</select>
					</div>
					<div class="column2">
						<select name="sem" id="sem">
							<option value="" class="list1">-- Select Semester* --</option>
							<option value="III" class="list1">SEMESTER III</option>
							<option value="IV" class="list1">SEMESTER IV</option>
							<option value="V" class="list1">SEMESTER V</option>
							<option value="VI" class="list1">SEMESTER VI</option>
							<option value="VII" class="list1">SEMESTER VII</option>
							<option value="VIII" class="list1">SEMESTER VIII</option>
						</select>
					</div>
					<div class="column1" style="margin-left:10em;margin-top:0em;">
						<select name="branch" id="branch">
							<option value="" class="list1">-- Select Branch* --</option>
							<option value="Information Technology" class="list1">Information Technology</option>
							<option value="Computer Science" class="list1">Computer Science</option>
							<option value="Electronics And Communication" class="list1">Electronics And Communication</option>
							<option value="Mechanical Engineering" class="list1">Mechanical Engineering</option>
							<option value="Metallurgy Engineering" class="list1">Metallurgy Engineering</option>
							<option value="Electrical Engineering" class="list1">Electrical Engineering</option>
							<option value="Civil Engineering" class="list1">Civil Engineering</option>
							<option value="Chemical Engineering" class="list1">Chemical Engineering</option>
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