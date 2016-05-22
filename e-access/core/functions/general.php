<?php
	function email($to,$subject,$body)
	{	mail($to,$subject,$body,'FROM:register&login@gamil.org');
		}
	function logged_in_redirect()
	{	if(logged_in()===true)
		{	header('Location:../pages/view.php');
			exit();
			}
		}
	function protect_page()
	{	if(logged_in()===false)
		{	header('Location:../pages/protected.php');
			exit();
			}
		}
	function array_sanitize(&$item)
	{	$item=htmlentities(strip_tags(mysqli_real_escape_string($GLOBALS['conn'],$item)));			
		}																			
	function sanitize($data)
	{	return htmlentities(strip_tags(mysqli_real_escape_string($GLOBALS['conn'],$data)));
		}
	function output_errors($errors)
	{	return implode('<br/>',$errors);
		}
	
?>