<?php 
	include '../core/init.php'; 
	protect_page();
	
	if(preg_match("/admin/",$user_data['user_desc'])==false)
		header('Location:access.php');
	$current='access';
	$here='move';
	include '../includes/header.php';
	
?>
<title>EACC : Batches</title>
<script type="text/javascript">
	function set()
	{	var file=document.getElementById("file").value;
		var val=file.split("\\");
		document.getElementById("fake").value='File: '+val[val.length-1];
		}
</script>
<?php
	include '../includes/top.php';
	
	if(isset($_FILES['file']))
	{	if(empty($_FILES['file']['name']))
			$errors[]='Please select a file to upload...';
		else
		{	$file_name=$_FILES['file']['name'];
			if(strtolower(end(explode('.',$file_name)))!=='sql')
				$errors[]='Invalid file selected...<br/>Please select a file of .sql type';
			$file_temp=$_FILES['file']['tmp_name'];
			}
		}
?>
	<div class="content">
		<div class="intro"> 
			<?php echo $user_data['first_name'].' '.$user_data['last_name']; ?>
		</div>
		<div class="container-type-2">
			<header>Select the result file to be imported...</header>
			<?php
				if(isset($_GET['success'])&&empty($_GET['success']))
				{	?>
						<div id="alert">
							<?php 
								echo '<br/>The selected result has been uploaded successfully in the server...<br/>';
							?>
							<div class="buts">
								<input type="button" onclick="location.href='../pages/view.php'" value="OK">
							</div><br/>
						</div>
					<?php
					}
				if(isset($_FILES['file'])&&empty($errors)===true)
				{	upload_result($servername,$username,$password,$file_name,$file_temp);
					header('Location:../pages/upload_result.php?batch='.$_POST['batch'].'&success');
					}
			?>		
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
				<form action="" method="post" enctype="multipart/form-data">
					<div class="column1">
						<input type="file" name="file" id="file" style="margin-left:10em" onchange="set()">
						<input type="button" id="fake" style="margin-left:10em" value="Choose File" readonly>
					</div>
					<div class="container-type-2-submit">
						<br/><br/><br/><br/><input type="submit" value="Submit">
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
	include '../includes/bottom.php';
	include '../includes/footer.php';
?>