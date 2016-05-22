<?php
	function upload_result($servername,$username,$password,$file,$loc)
	{	$file=sanitize($file);
		
		$file=explode('.',$file);
		unset($file[end(array_keys($file))]);
		$file=implode('.',$file);
		
		$str=$servername.PHP_EOL.$username.PHP_EOL.$password.PHP_EOL.$file.PHP_EOL.$loc;
		
		$query='CREATE DATABASE IF NOT EXISTS `'.$file.'` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci';
		mysqli_query($GLOBALS['conn'],$query);
		
		system('cmd /c ..\create.bat');
		file_put_contents('..\content.txt',$str);
		system('cmd /c ..\upload.bat');
		system('cmd /c ..\remove.bat');
		}	
	function check_complete_backup($batch)
	{	$batch=sanitize($batch);
		$test=0;
		
		$query='SELECT enroll FROM `'.$batch.'`.`enrolled students` WHERE 1';
		$result=mysqli_query($GLOBALS['conn'],$query);
		$enroll=array();
		while($x=mysqli_fetch_assoc($result))
			$enroll[]=$x['enroll'];
		$enroll='\''.implode('\',\'',$enroll).'\'';
		
		$query='SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE=\'BASE TABLE\' AND TABLE_SCHEMA=\'result\'';
		$table=mysqli_query($GLOBALS['conn1'],$query);
		$table=mysqli_fetch_assoc($table);
		foreach($table as $p)
		{	$query='SELECT stu_id FROM result.`'.$p.'` WHERE enroll IN('.$enroll.')';
			$result=mysqli_query($GLOBALS['conn1'],$query);
			if(mysqli_num_rows($result)>0)
				$test=1;
			}
		return $test;
		}
	function move_result($servername,$username,$password,$batch)
	{	$batch=sanitize($batch);
		$str=$servername.PHP_EOL.$username.PHP_EOL.$password.PHP_EOL.$_POST['batch'];
		system('cmd /c ..\create.bat');
		file_put_contents('..\content.txt',$str);
		system('cmd /c ..\backup.bat');
		system('cmd /c ..\remove.bat');
		$query='DROP DATABASE `'.$_POST['batch'].'`';
		mysqli_query($GLOBALS['conn'],$query);
		}
	function result_backup($sem,$branch)
	{	$sem=sanitize($sem);
		$branch=sanitize($branch);
		
		$query='SELECT MAX(batch) FROM `sem_'.$sem.'_'.$branch.'` WHERE 1';
		$batch=mysqli_fetch_array(mysqli_query($GLOBALS['conn1'],$query));
		$batch=$batch[0];
		if(empty($batch))
			return 0;
		
		$name=$batch.'-'.($batch+4);
		mysqli_query($GLOBALS['conn'],"CREATE DATABASE IF NOT EXISTS `$name` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci");
		
		$year='sem_'.$sem;
		
		$query='CREATE TABLE IF NOT EXISTS `'.$name.'`.`enrolled students`
				(	`stu_id` int NOT NULL auto_increment,
					`enroll` varchar(32) NOT NULL,
					`batch` int NOT NULL,
					`first_name` varchar(32) NOT NULL,
					`last_name` varchar(32) NOT NULL,
					`email` varchar(32) NOT NULL,
					`phone` varchar(15) NOT NULL,
					`branch` varchar(32) NOT NULL,
					PRIMARY KEY  (`stu_id`)	
					);';
		
		mysqli_query($GLOBALS['conn'],$query);

		$query='SELECT DISTINCT batch FROM e_access.`'.$year.'` WHERE 1';
		$result=mysqli_query($GLOBALS['conn'],$query);
		$res=array();
		while($x=mysqli_fetch_assoc($result))
			$res[]=$x['batch'];
		foreach($res as $x)
		{	$table=$x.'-'.($x+4);
			$query='INSERT INTO `'.$table.'`.`enrolled students`(enroll,batch,first_name,last_name,email,phone,branch) SELECT enroll,batch,first_name,last_name,email,phone,branch FROM e_access.`'.$year.'` WHERE batch=\''.$x.'\' AND branch=\''.$branch.'\' AND (enroll,branch) NOT IN(SELECT enroll,branch FROM `'.$name.'`.`enrolled students` WHERE 1) ORDER BY enroll';
			mysqli_query($GLOBALS['conn'],$query);
			}
		
		$query='CREATE TABLE IF NOT EXISTS `'.$name.'`.`subject_details`
				(	`id` int NOT NULL auto_increment,
					`sem` varchar(4) NOT NULL,
					`branch` varchar(32) NOT NULL,
					`course_name` text NOT NULL,
					`course_code` text NOT NULL,
					`credits` text NOT NULL,
					PRIMARY KEY  (`id`)	
					);';
		mysqli_query($GLOBALS['conn'],$query);
		
		$query='DELETE FROM `'.$name.'`.`subject_details` WHERE sem=\''.$sem.'\' AND branch=\''.$branch.'\'';
		mysqli_query($GLOBALS['conn'],$query);
				
		$query='INSERT INTO `'.$name.'`.`subject_details`(sem,branch,course_name,course_code,credits) SELECT sem,branch,course_name,course_code,credits FROM e_access.`subject_details` WHERE sem=\''.$sem.'\' AND branch=\''.$branch.'\'';
		mysqli_query($GLOBALS['conn'],$query);
			
		$query='DESCRIBE result.`sem_'.$sem.'_'.$branch.'`';
		$result=mysqli_query($GLOBALS['conn'],$query);
		$value='';
		$columns=array();
		while($x=mysqli_fetch_assoc($result))
		{	$value=$value.'`'.$x['Field'].'` '.$x['Type'].' NOT NULL '.$x['Extra'].',';
			if($x['Field']!='stu_id')
				$columns[]='`'.$x['Field'].'`';
			}
		$value=$value.'PRIMARY KEY (`stu_id`)';
		
		$query='DROP TABLE `'.$name.'`.`sem_'.$sem.'_'.$branch.'`';
		mysqli_query($GLOBALS['conn'],$query);
		
		$query='CREATE TABLE IF NOT EXISTS `'.$name.'`.`sem_'.$sem.'_'.$branch.'`
				(	'.$value.');';
		mysqli_query($GLOBALS['conn'],$query);
		
		$query='SELECT * FROM `sem_'.$sem.'_'.$branch.'` WHERE 1';
		$result=mysqli_query($GLOBALS['conn1'],$query);
		while($x=mysqli_fetch_assoc($result))
		{	$test=1;
			$marks=array();
			foreach($x as $key=>$value)
				if($key!=='stu_id'&&$key!=='enroll'&&$key!=='batch')
				{	$p=explode('_',$key);
					if(in_array(end($p),array_keys($marks)))
						$marks[end($p)]+=(int)$value;
					else
						$marks[end($p)]=(int)$value;
					}
			foreach($marks as $p)
				if($p<41)
					$test=0;
			if($test==0)
				$selected[]=$x['enroll'];
			}
		if(empty($selected))
			return 2;
		
		$selected='\''.implode('\',\'',$selected).'\'';		
		foreach($res as $x)
		{	$table=$x.'-'.($x+4);
			$query='INSERT INTO `'.$table.'`.`sem_'.$sem.'_'.$branch.'`('.implode(',',$columns).') SELECT '.implode(',',$columns).' FROM result.`sem_'.$sem.'_'.$branch.'` WHERE enroll IN ('.$selected.') AND batch=\''.$x.'\' ORDER BY enroll'; 
			mysqli_query($GLOBALS['conn'],$query);
			}
		return 1;
		}
	function delete_subject($sem,$branch,$subject)
	{	$sem=sanitize($sem);
		$branch=sanitize($branch);
		$subject=sanitize($subject);
		
		$a=strtolower($subject);
		if(in_array('lab',explode(' ',$a)))
			$query='ALTER TABLE `'.$table.'` DROP `accessment_'.$cur_sub.'`,DROP `attendance_'.$cur_sub.'`,DROP `viva_'.$cur_sub.'`,DROP `lab_'.$cur_sub.'`';			
		else if(in_array('workshop',explode(' ',$a)))
			$query='ALTER TABLE `'.$table.'` DROP `class work_'.$cur_sub.'`,DROP `final term exam_'.$cur_sub.'`';		
		else
			$query='ALTER TABLE `'.$table.'` DROP `minor1_'.$cur_sub.'`,DROP `minor2_'.$cur_sub.'`,DROP `ca_'.$cur_sub.'`,DROP `major_'.$cur_sub.'`';
		
		$query="SELECT course_name,course_code,credits FROM `subject_details` WHERE branch='$branch' AND sem='$sem'";
		$result=mysqli_query($GLOBALS['conn'],$query);
		$result=mysqli_fetch_assoc($result);
		
		$now_subject=explode(',',$result['course_name']);	
		$now_code=explode(',',$result['course_code']);
		$now_credit=explode(',',$result['credits']);
		
		foreach($now_subject as $key=>$value)
			if($value===$subject)
			{	unset($now_subject[$key]);
				unset($now_code[$key]);
				unset($now_credit[$key]);
				}
		$subject=implode(',',$now_subject);
		$code=implode(',',$now_code);
		$credit=implode(',',$now_credit);
		
		$query="UPDATE `subject_details` SET course_name='$subject',course_code='$code',credits='$credit' WHERE sem='$sem' AND branch='$branch'";
		
		mysqli_query($GLOBALS['conn'],$query);
		}
	function change_subject($sem,$branch,$subject,$code,$credit,$cur_sub)
	{	$sem=sanitize($sem);
		$branch=sanitize($branch);
		$subject=sanitize($subject);
		$code=sanitize($code);
		$credit=sanitize($credit);
		$cur_sub=sanitize($cur_sub);
		
		$a=strtolower($cur_sub);
		if(in_array('lab',explode(' ',$a)))
			$query='ALTER TABLE `'.$table.'` DROP `accessment_'.$cur_sub.'`,DROP `attendance_'.$cur_sub.'`,DROP `viva_'.$cur_sub.'`,DROP `lab_'.$cur_sub.'`';			
		else if(in_array('workshop',explode(' ',$a)))
			$query='ALTER TABLE `'.$table.'` DROP `class work_'.$cur_sub.'`,DROP `final term exam_'.$cur_sub.'`';		
		else
			$query='ALTER TABLE `'.$table.'` DROP `minor1_'.$cur_sub.'`,DROP `minor2_'.$cur_sub.'`,DROP `ca_'.$cur_sub.'`,DROP `major_'.$cur_sub.'`';
		mysqli_query($GLOBALS['conn1'],$query);
		
		$a=strtolower($subject);
		if(in_array('lab',explode(' ',$a)))
			$query="ALTER TABLE `$table` ADD (  `accessment_$subject` varchar(3) NOT NULL,
												  `attendance_$subject` varchar(3) NOT NULL,
												  `viva_$subject` varchar(3) NOT NULL,
												  `lab_$subject` varchar(3) NOT NULL)";
		else if(in_array('workshop',explode(' ',$a)))
			$query="ALTER TABLE `$table` ADD (  `class work_$subject` varchar(3) NOT NULL,
												  `final term exam_$subject` varchar(3) NOT NULL)";
		else
			$query="ALTER TABLE `$table` ADD (  `minor1_$subject` varchar(3) NOT NULL,
												  `minor2_$subject` varchar(3) NOT NULL,
												  `ca_$subject` varchar(3) NOT NULL,
												  `major_$subject` varchar(3) NOT NULL)";
		mysqli_query($GLOBALS['conn1'],$query);		
		
		$query="SELECT course_name,course_code,credits FROM `subject_details` WHERE branch='$branch' AND sem='$sem'";
		$result=mysqli_query($GLOBALS['conn'],$query);
		$result=mysqli_fetch_assoc($result);
		
		$now_subject=explode(',',$result['course_name']);	
		$now_code=explode(',',$result['course_code']);
		$now_credit=explode(',',$result['credits']);
		
		foreach($now_subject as $key=>$value)
			if($value===$cur_sub)
			{	$now_subject[$key]=$subject;
				$now_code[$key]=$code;
				$now_credit[$key]=$credit;
				}
		$subject=implode(',',$now_subject);
		$code=implode(',',$now_code);
		$credit=implode(',',$now_credit);
		$query="UPDATE `subject_details` SET course_name='$subject',course_code='$code',credits='$credit' WHERE sem='$sem' AND branch='$branch'";
		
		mysqli_query($GLOBALS['conn'],$query);
		}	
	function set_subjects($sem,$branch,$subject,$code,$credit)
	{	$sem=sanitize($sem);
		$branch=sanitize($branch);
		$subject=sanitize($subject);
		$code=sanitize($code);
		$credit=sanitize($credit);
				
		$table='sem_'.$sem.'_'.$branch;
		
		$a=strtolower($subject);
		
		if(in_array('lab',explode(' ',$a)))
			$query="ALTER TABLE `$table` ADD (  `accessment_$subject` varchar(3) NOT NULL,
												  `attendance_$subject` varchar(3) NOT NULL,
												  `viva_$subject` varchar(3) NOT NULL,
												  `lab_$subject` varchar(3) NOT NULL)";
		else if(in_array('workshop',explode(' ',$a)))
			$query="ALTER TABLE `$table` ADD (  `class work_$subject` varchar(3) NOT NULL,
												  `final term exam_$subject` varchar(3) NOT NULL)";
		else
			$query="ALTER TABLE `$table` ADD (  `minor1_$subject` varchar(3) NOT NULL,
												  `minor2_$subject` varchar(3) NOT NULL,
												  `ca_$subject` varchar(3) NOT NULL,
												  `major_$subject` varchar(3) NOT NULL)";
		mysqli_query($GLOBALS['conn1'],$query);		
		
		$query="SELECT course_name,course_code,credits FROM `subject_details` WHERE branch='$branch' AND sem='$sem'";
		$result=mysqli_query($GLOBALS['conn'],$query);
		$result=mysqli_fetch_assoc($result);
		
		if(empty($result)===true)
			$query="INSERT INTO `subject_details` (sem,branch,course_name,course_code,credits) VALUES ('$sem','$branch','$subject','$code','$credit')";
		else
		{	$now_subject=explode(',',$result['course_name']); $now_subject[]=$subject;	$subject=implode(',',$now_subject);
			$now_code=explode(',',$result['course_code']);	 $now_code[]=$code;		$code=implode(',',$now_code);
			$now_credit=explode(',',$result['credits']);		 $now_credit[]=$credit; $credit=implode(',',$now_credit);
			$query="UPDATE `subject_details` SET course_name='$subject',course_code='$code',credits='$credit' WHERE sem='$sem' AND branch='$branch'";
			}
		mysqli_query($GLOBALS['conn'],$query);
		
		}
	function get_prev_subjects($batch,$sem,$branch)
	{	$batch=sanitize($batch);
		$sem=sanitize($sem);
		$branch=sanitize($branch);
		
		$query='SELECT course_name,course_code,credits FROM `'.$batch.'`.`subject_details` WHERE branch=\''.$branch.'\' AND sem=\''.$sem.'\'';
		$result=mysqli_query($GLOBALS['conn'],$query);
		return mysqli_fetch_assoc($result);
		}
	function get_subjects($sem,$branch)
	{	$sem=sanitize($sem);
		$branch=sanitize($branch);
		
		$query="SELECT course_name,course_code,credits FROM `subject_details` WHERE branch='$branch' AND sem='$sem'";
		$result=mysqli_query($GLOBALS['conn'],$query);
		return mysqli_fetch_assoc($result);
		}	
	function grade($marks)
	{	$marks=sanitize($marks);
		if($marks>90&&$marks<=100)
			return 'A+';
		else if($marks>80&&$marks<=90)
			return 'A';
		else if($marks>70&&$marks<=80)
			return 'B+';
		else if($marks>60&&$marks<=70)
			return 'B';
		else if($marks>50&&$marks<=60)
			return 'C+';
		else if($marks>40&&$marks<=50)
			return 'C';
		else
			return 'F';
		}
	function clear_result($sem,$branch)
	{	$year='sem_'.$sem;
		$table='sem_'.$sem.'_'.$branch;
		$selected=array();
		$query='SELECT * FROM `'.$table.'` WHERE 1';
		$result=mysqli_query($GLOBALS['conn1'],$query);
		while($x=mysqli_fetch_assoc($result))
		{	$test=1;
			$marks=array();
			foreach($x as $key=>$value)
				if($key!=='stu_id'&&$key!=='enroll'&&$key!=='batch')
				{	$p=explode('_',$key);
					if(in_array(end($p),array_keys($marks)))
						$marks[end($p)]+=(int)$value;
					else
						$marks[end($p)]=(int)$value;
					}
			foreach($marks as $p)
				if($p<41)
					$test=0;
			if($test==1)
				$selected[]=$x['enroll'];
			}
		if(empty($selected))
			return 1;
		
		$selected='\''.implode('\',\'',$selected).'\'';
		$query='DELETE FROM `'.$year.'` WHERE enroll IN('.$selected.')';
		mysqli_query($GLOBALS['conn'],$query);
		
		$query='DELETE FROM `'.$table.'` WHERE enroll IN('.$selected.')';
		mysqli_query($GLOBALS['conn1'],$query);
		
		$query='SELECT * FROM `'.$table.'` WHERE 1';
		$result=mysqli_query($GLOBALS['conn1'],$query);
		
		if(mysqli_num_rows($result)==0)
		{	$query='DROP TABLE `'.$table.'`';
			mysqli_query($_GLOBALS['conn1'],$query);
			}
		}
	function get_result($batch,$sem,$branch,$subject,$exam)
	{	$batch=sanitize($batch);
		$sem=sanitize($sem);
		$branch=sanitize($branch);
		$subject=sanitize($subject);
		$exam=sanitize($exam);
		$sem='sem_'.$sem;
		$table=$sem.'_'.$branch;
		$a=strtolower($subject);
		if(in_array('lab',explode(' ',$a)))	
		{	if($exam==='accessment'||$exam==='attendance'||$exam==='viva'||$exam==='lab')
				$field='`'.$exam.'_'.$subject.'`';
			else if($exam==='total')
				$field='`accessment_'.$subject.'`,`attendance_'.$subject.'`,`viva_'.$subject.'`,`lab_'.$subject.'`';
			}	
		else if(in_array('workshop',explode(' ',$a)))
		{	if($exam==='class work'||$exam==='final term exam')
				$field='`'.$exam.'_'.$subject.'`';
			else if($exam==='total')
				$field='`class work_'.$subject.'`,`final term exam_'.$subject.'`';
			}
		else	
		{	if($exam==='minor1'||$exam==='minor2'||$exam==='major'||$exam==='ca')
				$field='`'.$exam.'_'.$subject.'`';
			else if($exam==='total')
				$field='`minor1_'.$subject.'`,`minor2_'.$subject.'`,`major_'.$subject.'`,`ca_'.$subject.'`';
			}
		if($batch==='')
			$query='SELECT enroll,batch,'.$field.' FROM `'.$table.'` WHERE 1 ORDER BY batch DESC,enroll';
		else
			$query='SELECT enroll,batch,'.$field.' FROM `'.$batch.'`.`'.$table.'` WHERE 1 ORDER BY batch DESC,enroll';
		$query=mysqli_query($GLOBALS['conn1'],$query);
		return $query;
		}	
	function add_marks($data,$branch,$exam,$sem,$subject)
	{	array_walk($data,'array_sanitize');
		$sem=sanitize($sem);
		$branch=sanitize($branch);
		$subject=sanitize($subject);
		$exam=sanitize($exam);
		$table='sem_'.$sem.'_'.$branch;
		
		$field=$exam.'_'.$subject;
		$enroll=array_keys($data);
		foreach($enroll as $x)
		{	mysqli_query($GLOBALS['conn1'],"UPDATE `$table` SET `$field`=$data[$x] WHERE enroll='$x'");
			}
		}
	function marks_form($sem,$branch,$subject,$exam)
	{	$sem=sanitize($sem);
		$branch=sanitize($branch);
		$subject=sanitize($subject);
		$exam=sanitize($exam);
		
		$table='sem_'.$sem.'_'.$branch;
		
		$field=$exam.'_'.$subject;
		$query=mysqli_query($GLOBALS['conn1'],"SELECT enroll,batch,`$field` FROM `$table` WHERE 1 ORDER BY batch DESC,enroll");
		return $query;
		}
	function delete_student($enroll,$year,$branch,$sem,$batch)
	{	$enroll=sanitize($enroll);
		$year=sanitize($year);
		$branch=sanitize($branch);
		$sem=sanitize($sem);
		$batch=(int)$batch;
		
		$query='DELETE FROM '.$year.' WHERE enroll=\''.$enroll.'\' AND branch=\''.$branch.'\'';
		mysqli_query($GLOBALS['conn'],$query);
		
		$query='DELETE FROM `sem_'.$sem.'_'.$branch.'` WHERE enroll=\''.$enroll.'\'';
		mysqli_query($GLOBALS['conn1'],$query); 
		
		$query='SELECT DISTINCT `TABLE_SCHEMA` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA` LIKE \'____-____%\'';
		$result=mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],$query));
		
		if(in_array($batch.'-'.($batch+4),$result))
		{	$sem=array('iii','iv','v','vi','vii','viii');
			$table=array();
			$table[]='`'.$batch.'-'.($batch+4).'`.`enrolled students`';
			foreach($sem as $x)
				$table[]='`'.$batch.'-'.($batch+4).'`.`sem_'.$x.'_'.$branch.'`';
			
			foreach($table as $x)
			{	$query='DELETE FROM '.$x.' WHERE enroll=\''.$enroll.'\'';
				mysqli_query($GLOBALS['conn'],$query);
				}
			}
		}
	function register_student($register_data)
	{	array_walk($register_data,'array_sanitize');
		
		$year='sem_'.$register_data['sem'];
		$fields=implode(',',array_keys($register_data));
		$data='\''.implode('\',\'',$register_data).'\'';
		mysqli_query($GLOBALS['conn'],"INSERT INTO $year ($fields) VALUES ($data)");
		}	
	function update_student($update_data)
	{	array_walk($update_data,'array_sanitize');
		$year='sem_'.$update_data['sem'];		
		if($update_data['cur_branch']!==$update_data['branch'])	
		{	$query='DELETE FROM `sem_'.$update_data['sem'].'_'.$update_data['cur_branch'].'` WHERE enroll=\''.$update_data['enroll'].'\'';
			mysqli_query($GLOBALS['conn1'],$query);
			}
		$result=mysqli_query($GLOBALS['conn'],'SELECT stu_id FROM '.$year.' WHERE enroll=\''.$update_data['enroll'].'\' AND branch=\''.$update_data['cur_branch'].'\'');
		$result=mysqli_fetch_assoc($result);
		
		unset($update_data['enroll'],$update_data['cur_branch'],$update_data['sem']);
		foreach($update_data as $field=>$data)
		{	$update[]=$field.' = \''.$data.'\'';
			}
		$query='UPDATE '.$year.' SET '.implode(',',$update).' WHERE stu_id='.$result['stu_id'];
		mysqli_query($GLOBALS['conn'],$query);
		}		
	function student_exists($enroll,$sem,$branch)
	{	$enroll=sanitize($enroll);
		$sem=sanitize($sem);
		$branch=sanitize($branch);
		$year='sem_'.$sem;
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM $year WHERE enroll='$enroll' AND branch='$branch'");
		return (mysqli_num_rows($query)==1)?true:false;			
		}
	function student_email_exists($email,$sem)
	{	$email=sanitize($email);
		$sem=sanitize($sem);
		$year='sem_'.$sem;
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM $year WHERE email='$email'");
		return (mysqli_num_rows($query)==1)?true:false;			
		}
	function prev_search($name,$batch,$branch)
	{	$name=sanitize($name);
		$batch=sanitize($batch);
		$branch=sanitize($branch);
		
		if(empty($name)===true)
			$query="SELECT enroll,batch,first_name,last_name,email,phone,branch FROM `$batch`.`enrolled students` WHERE branch='$branch' ORDER BY enroll";
		else if(empty($branch)===true)	
			$query="SELECT enroll,batch,first_name,last_name,email,phone,branch FROM `$batch`.`enrolled students` WHERE first_name='$name' ORDER BY enroll";
		else 
			$query="SELECT enroll,batch,first_name,last_name,email,phone,branch FROM `$batch`.`enrolled students` WHERE first_name='$name' AND branch='$branch' ORDER BY enroll";
		
		$result=mysqli_query($GLOBALS['conn'],$query);
		return $result;
		}
	function search_by_name($name,$sem,$branch)
	{	$name=sanitize($name);
		$sem=sanitize($sem);
		$branch=sanitize($branch);
		$year='sem_'.$sem;
		
		if(empty($name)===true)
		{	if(empty($branch)===false&&empty($year)===true)
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iii` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_iii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iv` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_iv` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_v` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_v` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vi` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_vi` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vii` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_vii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_viii` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_viii` WHERE 1) ORDER BY sem,enroll";
			else if(empty($branch)===false&&empty($year)===false)
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM $year WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM $year WHERE 1) ORDER BY enroll";
			}
		else if(empty($branch)===true)	
		{	if(empty($name)===false&&empty($year)===true)
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iii` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_iii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iv` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_iv` WHERE 1)  
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_v` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_v` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vi` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_vi` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vii` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_vii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_viii` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_viii` WHERE 1) ORDER BY sem,enroll";
			else if(empty($name)===false&&empty($year)===false)
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM $year WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM $year WHERE 1) ORDER BY enroll";
			}
		else if(empty($year)===true)
		{	if(empty($name)===false&&empty($branch)===true)
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iii` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_iii` WHERE 1)  
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iv` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_iv` WHERE 1)  
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_v` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_v` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vi` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_vi` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vii` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_vii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_viii` WHERE first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_viii` WHERE 1) ORDER BY sem,enroll";
			else if(empty($name)===true&&empty($branch)===false)
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iii` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_iii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iv` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_iv` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_v` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_v` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vi` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_vi` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vii` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_vii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_viii` WHERE branch='$branch' AND batch IN(SELECT MAX(batch) FROM `sem_viii` WHERE 1) ORDER BY sem,enroll";
			else
				$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iii` WHERE branch='$branch' AND first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_iii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_iv` WHERE branch='$branch' AND first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_iv` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_v` WHERE branch='$branch' AND first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_v` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vi` WHERE branch='$branch' AND first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_vi` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_vii` WHERE branch='$branch' AND first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_vii` WHERE 1) 
						UNION SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM `sem_viii` WHERE branch='$branch' AND first_name='$name' AND batch IN(SELECT MAX(batch) FROM `sem_viii` WHERE 1) ORDER BY sem,enroll";
			}
		else 
			$query="SELECT enroll,batch,first_name,last_name,email,phone,sem,branch FROM $year WHERE first_name='$name' AND branch='$branch' AND batch IN(SELECT MAX(batch) FROM $year WHERE 1) ORDER BY enroll";
		$result=mysqli_query($GLOBALS['conn'],$query);
		return $result;
		}
	function delete_user($user_id)
	{	mysqli_query($GLOBALS['conn'],"DELETE FROM users WHERE user_id=$user_id");
		}
	function update_user($update_data)
	{	GLOBAL $user_data;
		array_walk($update_data,'array_sanitize');
		$update=array();
		foreach($update_data as $field=>$data)
		{	$update[]=$field.' = \''.$data.'\'';
			}
		mysqli_query($GLOBALS['conn'],'UPDATE users SET '.implode(',',$update).' WHERE user_id='.$user_data['user_id']);
		}	
	function activate($email,$email_code)
	{	$email= sanitize($email);
		$email_code=sanitize($email_code);
		
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE email='$email' AND email_code='$email_code' AND active=0");
		if(mysqli_num_rows($query)==1)
		{	mysqli_query($GLOBALS['conn'],"UPDATE users SET active=1 WHERE email='$email'");
			return true;
			}
		else
			return false;
		}
	function change_password($user_id,$password)
	{	$user_id=(int)$user_id;
		$password=md5($password);
		mysqli_query($GLOBALS['conn'],"UPDATE users SET password='$password' WHERE user_id=$user_id");
		}
	function register_user($register_data)
	{	array_walk($register_data,'array_sanitize');
		
		$register_data['password']=md5($register_data['password']);
		foreach($register_data as $field=>$data)
			$update[]=$field.' = \''.$data.'\'';
		$query='UPDATE users SET '.implode(',',$update).' WHERE first_name=\''.$register_data['first_name'].'\' AND last_name=\''.$register_data['last_name'].'\' AND department=\''.$register_data['department'].'\'';
		mysqli_query($GLOBALS['conn'],$query);
		$url='http://localhost/e-access/pages/activate.php?email='.$register_data['email'].'&email_code='.$register_data['email_code'];
		echo 'Hello ' . $register_data['first_name'] .
		',<br/><br/>you need to activate your account, so copy the link below to url tab:<br/><br/>
		<a href='.$url.'>'.$url.'</a>
		<br/><br/>-nit srinagar';
		die();
		/*	email($register_data['email'],'activate your account','
		Hello ' . $register_data[first_name] .
		',<br/><br/>you need to activate your account, so use the link below:<br/><br/>
		<a href='.$url.'>'.$url.'</a>
		<br/><br/>-nit srinagar
		");*/
		}
	function recover_password($email,$username,$first_name)
	{	$code=substr(md5(rand(999,999999)),2,6);
		
		mysqli_query($GLOBALS['conn'],"UPDATE users SET email_code='$code' WHERE username='$username'");
		
		$url='http://localhost/e-access/pages/verify.php';
		echo 'Hello ' . $first_name .
		',<br/><br/>your confirmation code is : '.$code.'<br/><br/> use the link below to recover your password:<br/><br/>
		<a href='.$url.'>'.$url.'</a>
		<br/><br/>-nit srinagar';
		die();
		/*email($email,'recover password','
		Hello ' . $first_name .
		',<br/><br/>your confirmation code is : '.$code.' <br/><br/> use the link below to recover your password:<br/><br/>
		<a href='.$url.'>'.$url.'</a>
		<br/><br/>-nit srinagar');*/
		
		}
	function reset_password($username,$password)
	{	$username=sanitize($username);
		$password=md5($password);
		
		mysqli_query($GLOBALS['conn'],"UPDATE users SET password='$password' WHERE username='$username'");
		
		$code=mysqli_query($GLOBALS['conn'],"SELECT email_code FROM users WHERE username='$username'");
		$code=mysqli_fetch_assoc($code);
		
		$code=$code['email_code'].substr(md5(time()),5,6);
		mysqli_query($GLOBALS['conn'],"UPDATE users SET email_code='$code' WHERE username='$username'");
		}
	function set_user($first_name,$last_name,$department,$desc)
	{	$first_name=sanitize($first_name);
		$last_name=sanitize($last_name);
		$department=sanitize($department);
		$desc=sanitize($desc);
		
		$first_name=trim($first_name);
		$last_name=trim($last_name);
		
		$result=mysqli_query($GLOBALS['conn'],'SELECT user_id,first_name,last_name,department,user_desc FROM users WHERE INSTR(user_desc,\''.$desc.'\')>0');
		while($x=mysqli_fetch_assoc($result))	
			if($x['first_name']!=='\''.$first_name.'\''&&$x['last_name']!=='\''.$last_name.'\''&&$x['department']!=='\''.$department.'\'')
				$value=$x;
		
		if(empty($value['mail'])===false)
		{	//	send mail for confirmation from user with current rights...
			}
		$detail=$value['user_desc'];
		$detail=explode(',',$detail);
		$new_desc=array();
		
		foreach($detail as $x)
			if($x!==$desc)
				$new_desc[]=$x;	
		$new_desc=implode(',',$new_desc);
		if(empty($new_desc)===true)
			delete_user($value['user_id']);
		else
			mysqli_query($GLOBALS['conn'],'UPDATE users SET user_desc=\''.$new_desc.'\' WHERE user_id=\''.$value['user_id'].'\'');
					
		$result=check_account($first_name,$last_name,$department);
		$result=mysqli_fetch_assoc($result);
		if(empty($result))
			$query="INSERT INTO users (first_name,last_name,department,user_desc) VALUES ('$first_name','$last_name','$department','$desc')";
		else
		{	$value=array();
			$value[]=$result['user_desc'];
			$value[]=$desc;
			$value=implode(',',$value);
			$query="UPDATE users SET user_desc='$value' WHERE first_name='$first_name' AND last_name='$last_name' AND department='$department'";
			}
		mysqli_query($GLOBALS['conn'],$query);
		}
	function check_desc($desc)
	{	$desc=sanitize($desc);
		$query='SELECT user_id,first_name,last_name,department,user_desc FROM users WHERE INSTR(user_desc,\''.$desc.'\')>0 ORDER BY first_name';
		$result=mysqli_query($GLOBALS['conn'],$query);
		return $result;
		}
	function check_account($first_name,$last_name,$department)
	{	$first_name=sanitize($first_name);
		$last_name=sanitize($last_name);
		$department=sanitize($department);
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE first_name='$first_name' AND last_name='$last_name' AND department='$department'");
		return ($query);		
		}
	function user_data($user_id)
	{	$data=array();
		$user_id=(int)$user_id;
		$func_num_args=func_num_args();				
		$func_get_args=func_get_args();				
		if($func_num_args>1)
		{	unset($func_get_args[0]);
			$fields='`'.implode('`,`',$func_get_args).'`';
			$data=mysqli_fetch_assoc(mysqli_query($GLOBALS['conn'],"SELECT $fields FROM users WHERE user_id=$user_id"));
			return $data; 
			}
		}
	function logged_in()
	{	return (isset($_SESSION['user_id']))?true:false;
		}
	function user_exists($username)
	{	$username=sanitize($username);
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE username='$username'");
		return (mysqli_num_rows($query)==1)?true:false;			
		}
	function email_exists($email)
	{	$email=sanitize($email);
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE email='$email'");
		return (mysqli_num_rows($query)==1)?true:false;			
		}
	function user_active($username)
	{	$username=sanitize($username);
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE username='$username' AND active=1");		
		return (mysqli_num_rows($query)==1)?true:false;				
		}
	function user_id_from_username($username)
	{	$username=sanitize($username);
		$query=mysqli_query($GLOBALS['conn'],"SELECT user_id FROM users WHERE username='$username'");
		$row= mysqli_fetch_assoc($query);
		return $row['user_id'];
		}
	function login($username,$password)
	{	$user_id=user_id_from_username($username);
		$username=sanitize($username);
		$password=md5($password);
		$query=mysqli_query($GLOBALS['conn'],"SELECT * FROM users WHERE username ='$username' AND password='$password'"); 
		$rows=mysqli_num_rows($query);
		return ($rows==1)?($user_id):false;
		}
?>