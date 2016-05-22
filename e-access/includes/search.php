<?php
	protect_page();
	
	if(empty($_POST)===false)
	{	$check=1;
		if(empty($_POST['list'])===true&&empty($_POST['name'])===true&&empty($_POST['branch'])===true)
			$errors[]='Please select or enter the details to search for...';
		else if(empty($_POST['list'])===false&&empty($_POST['name'])===true&&empty($_POST['branch'])===true)
			$errors[]='Please select branch or enter name to search in a year...';
		else if($cur_location==='prev_enrolled.php'&&empty($_POST['list']))
			$errors[]='Please select a batch to search into...';
		}
	
	if(!(isset($_GET['success'])&&empty($_GET['success'])))
	{	if(empty($_POST)===false && empty($errors)===true)		
		{	if($cur_location==='prev_enrolled.php')
				$result=prev_search($_POST['name'],$_POST['list'],$_POST['branch']);
			else
				$result=search_by_name($_POST['name'],$_POST['list'],$_POST['branch']);
			}
		
		if($cur_location==='prev_enrolled.php')
		{	$query='SELECT DISTINCT `TABLE_SCHEMA` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` LIKE \'____-____%\'';
			$batch=mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],$query));
			}
		?>	
		<div class="select-to-view-students">
			<form method="post" action="">
				<select name="list" id="list">
					<?php
						if($cur_location==='prev_enrolled.php')
						{	?>
								<option value="" class="list1">-- Select Batch* --</option>
							<?php
							foreach($batch as $x)
							{	?>
									<option value="<?php echo $x;?>" class="list1"><?php echo $x;?></option>
								<?php
								}
							}
						else
						{	?>
								<option value="" class="list1">-- Select Semester --</option>
								<option value="III" class="list1">Semester III</option>
								<option value="IV" class="list1">Semester IV</option>
								<option value="V" class="list1">Semester V</option>
								<option value="VI" class="list1">Semester VI</option>
								<option value="VII" class="list1">Semester VII</option>
								<option value="VIII" class="list1">Semester VIII</option>
							<?php							
							}
					?>
				</select>
				<select name="branch" id="branch">
					<option value="" class="list1">-- Select Branch --</option>
					<option value="Information Technology" class="list1">Information Technology</option>
					<option value="Computer Science" class="list1">Computer Science</option>
					<option value="Electronics And Communication" class="list1">Electronics And Communication</option>
					<option value="Mechanical Engineering" class="list1">Mechanical Engineering</option>
					<option value="Metallurgy Engineering" class="list1">Metallurgy Engineering</option>
					<option value="Electrical Engineering" class="list1">Electrical Engineering</option>
					<option value="Civil Engineering" class="list1">Civil Engineering</option>
					<option value="Chemical Engineering" class="list1">Chemical Engineering</option>
				</select><br/><br/>
				<span class="search-by-name">
					<input type="text" name="name" class="std-search" placeholder="Search By First Name"/>
				</span>
				<input type="submit" name="search" value="Search" />
			</form><br/><br/>
		</div>	
		<?php
		if(empty($errors)===false)
		{	?>
				<div class="columns-type-2">
					<div id="errors">
						<?php echo output_errors($errors);?>
					</div>
					<div class="images">
						<img src="../images/erecords.jpg" alt="Records"/>
					</div>
				</div>
			<?php
			}
		if(empty($_POST)===false && empty($errors)===true)	
		{	?>
				<div class="columns-type-2">
					<?php
						if(mysqli_num_rows($result)==0)
						{	?>
								<div id="errors">
									<?php echo 'sorry... no result found for your search...';?>
								</div>	
								<div class="images">
									<img src="../images/erecords.jpg" alt="Records"/>
								</div>
							<?php
							}
						else
						{	?>
								<div class="rec-search">		
									<table>
										<caption>SEARCH RESULTS</caption>
										<tr>
											<th class="roll">S.no.</th>
											<th class="enroll">Enroll.No.</th>
											<th class="batch">Batch</th>
											<th class="name">First name</th>
											<th class="name">Last name</th>
											<th class="email">Email</th>
											<th class="phone">Contact</th>
											<th class="sem">Semester</th>
											<th class="branch">Branch</th>
										</tr>
										<tbody class="results">	
											<?php
												$i=0;
												while($row=mysqli_fetch_assoc($result))	
												{	$i+=1;
													?>
													<tr>
														<td>
															<?php echo $i.'.'; ?>
														</td>
														<?php
															if(preg_match("/coord_/",$user_data['user_desc']))
															{	?>
																	<td title="Click to edit student's details">
																		<?php echo '<a href=\'edit_details.php?enroll='.$row['enroll'].'&sem='.$row['sem'].'&branch='.$row['branch'].'\'>'.$row['enroll'].'</a>'; ?>
																	</td>
																<?php
																}
															else
															{	?>
																	<td>
																		<?php echo $row['enroll']; ?>
																	</td>
																<?php															
																}
														?>
														<td>
															<?php echo $row['batch']; ?>
														</td>
														<td>
															<?php echo $row['first_name']; ?>
														</td>
														<td>
															<?php echo $row['last_name']; ?>
														</td>
														<td>
															<?php echo $row['email']; ?>
														</td>
														<td>
															<?php echo $row['phone']; ?>
														</td>
														<td>
															<?php echo $row['sem']; ?>
														</td>
														<td>
															<?php echo $row['branch']; ?>
														</td>
													</tr>
													<?php
													}
											?>
										</tbody>
									</table>
								</div>	
							<?php
							}
					?>
				</div>
			<?php
			}
		}
?>