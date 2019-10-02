<?php
//function to check admin login
function adminlogin()
{
	$userid=tep_db_prepare_input($_POST['login']);
	$password=tep_db_prepare_input(md5($_POST['password']));
	$sql="SELECT * FROM ".TABLE_USERS." WHERE email='".tep_db_input($userid)."' AND password='".tep_db_input($password)."'";
	$result=tep_db_query($sql);
	$numrows=tep_db_num_rows($result);
	$row=tep_db_fetch_assoc($result);
	
	if($userid=='' && $_POST['password']=='')
	{
		tep_redirect("adm_login.php?err=".urlencode("Sorry user id and password canot left blank!"));
	}
	else if($numrows>(int)0)
	{
		tep_session_register("user_name");
		tep_session_register("user_id");
		$_SESSION['user_name']=$row['user_name'];
		$_SESSION['user_id']  =$row['id'];
		
		if(tep_session_is_registered('previous_page')){
			tep_redirect($_SESSION['previous_page']);	//redirecting to previous page
		}else{
			tep_redirect("adm_home.php");
		}	
		
	}
	else
	{
		$sql="UPDATE administrators SET un_date=now(),un_ipaddress='".USER_IP."' WHERE login_ip='".USER_IP."'";
		tep_db_query($sql);
		tep_redirect("adm_login.php?err=".urlencode("Wrong userid or password!"));
	}
	
}
//end
/*
	Delete a profile image where profile = $userId
*/
function _deleteProfileImage($userId)
{
    // we will return the status
    // whether the image deleted successfully
    $deleted = false;

	// get the image(s)
    $sql = "SELECT image 
            FROM  site_users
            WHERE id = $userId";
    $result = tep_db_query($sql);
    
    if (tep_db_num_rows($result)) {
        while ($row = tep_db_fetch_assoc($result)) {
	        // delete the image file
    	    $deleted = @unlink(UPLOAD_USER_IMAGE . $row['image']);
					   @unlink(UPLOAD_USER_IMAGE . 'small_'.$row['image']);
					   @unlink(UPLOAD_USER_IMAGE . 'mini_'.$row['image']);
		}	
    }
    
    return $deleted;
}
/*end*/
//functin to get the profile image
function getProfileImage($userId)
{
	 $sql   = "SELECT image 
            FROM  site_users
            WHERE id='$userId'";
	$result =tep_db_query($sql);
	$row	=tep_db_fetch_assoc($result);
	return $row['image'];		
}
//end
//admin logout function
function adminlogout()
{
	if(tep_session_is_registered("user_id")){
		$sql="UPDATE ".TABLE_ADMINISTRATORS." SET login_date=now(),login_ip='".USER_IP."' WHERE id='".$_SESSION['user_id']."'";
		$res=tep_db_query($sql);
		tep_session_unregister("user_name");
		tep_session_unregister("user_id");
		tep_session_unregister("previous_page");
    }
	tep_redirect("adm_login.php?msg=Logout successfully.");
}
//end
//check admin login
function check(){
	if(empty($_SESSION['user_id']) || empty($_SESSION['user_name']))
	{
		//tep_session_register("previous_page");
		//$_SESSION['previous_page']=REQUEST_URL;
		
		header('location:login.php');
	}
}
//end
//function 
function userLogin()
{
	$user_id	 =tep_db_input($_POST['user_id']);
	$password	 =tep_db_input($_POST['password']);
	
	$sql="SELECT id,user_id FROM ".TABLE_USERS." WHERE user_id='$user_id' AND password='$password'";
	$result=tep_db_query($sql);
	$numrows=tep_db_num_rows($result);
	$row=tep_db_fetch_assoc($result);
	if($user_id=='' && $_POST['password']=='')
	{
		header("location:login.php?err=".urlencode("login_blank"));
		exit(0);
	}
	else if($numrows>(int)0)
	{
		session_register("username");
		session_register("userid");
		$_SESSION['userid']=$row['id'];
		$_SESSION['username']  =$row['user_id'];
		//header("location:index.php");
		if(tep_session_is_registered('oldpage'))
		{
			tep_redirect($_SESSION['oldpage']);
						
		}else{
			header("location:blog.php");
		}	
	}
	else
	{
		//$sql="UPDATE administrators SET un_date=now(),un_ipaddress='".USER_IP."' WHERE login_ip='".USER_IP."'";
		//tep_db_query($sql);
		header("location:login.php?err=".urlencode("login_error"));
		exit(0);
	}
}
//end
//check user login
function checkUser()
{
	if(empty($_SESSION['user']) || empty($_SESSION['userid']))
	{
		header("location:login.php");
	}
}
//end
//logout user
function logoutUser()
{
	if(session_is_registered("userid"))
	{
		session_unregister("user");
		session_unregister("userid");
		session_unregister("username");
		session_unregister("oldpage");
		header("location:index.php");
		exit(0);
	}	
}
//end
#admin details here
function adminDetails()
{
	 $query="SELECT * FROM ".TABLE_ADMINISTRATORS;
	 $result=tep_db_query($query);
	 $row	=tep_db_fetch_assoc($result);
	 define("ADMIN_MAIL",$row['email']);
	 define("ADMIN_LOGIN_DATE",$row['login_date']);
	 define("ADMIN_UNSUCCESS_DATE",$row['un_date']);
	 define("ADMIN_IP_ADDRESS",$row['login_ip']);
	 define("ADMIN_UNSUCCESS_IP",$row['un_ipaddress']);
	 define("USER_TYPE",$row['user_type']);
	 
}

//function to check duplicate user
function checkDuplicate($user_name)
{
	$result=tep_db_query("select user_name from ".TABLE_USERS." where user_name='".$user_name."'");
	return tep_db_num_rows($result);
}
//end

//function get user name
function getUserDetails($user_id)
{
	$result=tep_db_query("select * from ".TABLE_USERS." where adm_no='".$user_id."'");
	$row=tep_db_fetch_assoc($result);
	return $row;
}
//end

//contat info function
function numContacts($table)
{
	$sql="SELECT * FROM $table WHERE status='active'";
	$result=tep_db_query($sql);
	$numrows=tep_db_num_rows($result);
	return $numrows;
}
//end
#FILE UPLOAD
function fileuplaod($newfile,$extension,$file_name,$path)
{
	if($extension ==".doc")
	{
		move_uploaded_file($file_name,$path);
		chmod($path,0644);
		$msg='';
	}
	else{
		$msg="Please select any document type file!";
	}
	return $msg;
}	
#END FILE UPLOAD

//projects function
function projects()
{
	$sql="SELECT id,project_name FROM projects";
	$result=tep_db_query($sql);
/*	while($row=tep_db_fetch_assoc($result))
	{
		$projects=array($row['id'];
	}
*/	
	return $result;
}
//end

/**************************
	Paging Functions
***************************/

function getPagingQuery($sql, $itemPerPage = 10)
{
	if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
		$page = (int)$_GET['page'];
	} else {
		$page = 1;
	}
	
	// start fetching from this row number
	$offset = ($page - 1) * $itemPerPage;
	
	return $sql . " LIMIT $offset, $itemPerPage";
}

/*
	Get the links to navigate between one result page to another.
	Supply a value for $strGet if the page url already contain some
	GET values for example if the original page url is like this :
	
	http://www.phpwebcommerce.com/plaincart/index.php?c=12
	
	use "c=12" as the value for $strGet. But if the url is like this :
	
	http://www.phpwebcommerce.com/plaincart/index.php
	
	then there's no need to set a value for $strGet
	
	
*/
function getPagingLink($sql, $itemPerPage = 10, $strGet = '')
{
	$result        = tep_db_query($sql);
	$pagingLink    = '';
	$totalResults  = tep_db_num_rows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	
	// how many link pages to show
	$numLinks      = 10;

		
	// create the paging links only if we have more than one page of results
	
	if ($totalPages > 1) {
	
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}
		
		// print 'previous' link only if we're not
		// on page one
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = "<a href=\"$self?page=$page&$strGet/\">&laquo; Prev</a>";
			} else {
				$prev = "<a href=\"$self?$strGet\"  >&laquo; Prev</a>";
			}	
				
			$first = "<a href=\"$self?$strGet\" >First</a>";
		} else {
			$prev  = '<span class="disabled_tnt_pagination">&laquo; Prev</span>'; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = "<a href=\"$self?page=$page&$strGet\" class=\"prevnext\">next &raquo;</a>";
			$last = "<a href=\"$self?page=$totalPages&$strGet\">Last</a>";
		} else {
			$next = '<span class="disabled_tnt_pagination"> next &raquo;</span>'; // we're on the last page, don't show 'next' link
			$last = ''; // nor 'last page' link
		}

		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;		
		
		$end   = min($totalPages, $end);
		
		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = "<span class=\"active_tnt_link\"> $page </span>";   // no need to create a link to current page
			} else {
				if ($page == 1) {
					$pagingLink[] = "<a href=\"$self?$strGet\"> $page </a>";
				} else {	
					$pagingLink[] = "<a href=\"$self?page=$page&$strGet\"> $page </a>";
				}	
			}
			
			$moresing=($totalPages!=$page) ? '. . . .  '.'<span class="active_tnt_link">'.$totalPages.'</span>' : '';
		}
		
		$pagingLink = implode('', $pagingLink);
		// return the page navigation link
		$pagingLink = $prev .$pagingLink .$moresing. $next;
	}
		
	return $pagingLink;
}

#end
//================================PHOTO COMMENT PAGGING=================
function getPhotoPagingLink($sql, $itemPerPage = 10, $strGet = '')
{
	$result        = tep_db_query($sql);
	$pagingLink    = '';
	$totalResults  = tep_db_num_rows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	
	// how many link pages to show
	$numLinks      = 10;

		
	// create the paging links only if we have more than one page of results
	if ($totalPages > 1) {
	
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}
		
		// print 'previous' link only if we're not
		// on page one
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = " <a href=\"$self?page=$page&$strGet\"><img src=\"images/left_n.gif\" border=\"0\"></a> ";
			} else {
				$prev = " <a href=\"$self?page=$page&$strGet\"><img src=\"images/left_n.gif\" border=\"0\"></a> ";
			}	
				
			$first = " <a href=\"$self?$strGet\">First</a> // ";
		} else {
			$prev  = '<img src="images/left_o.gif" border="0">'; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = " <a href=\"$self?page=$page&$strGet\"><img src=\"images/right_n.gif\" border=\"0\"></a> ";
			//$prev = " <img src=\"images/left_o.gif\" border=\"0\"> ";
			$last = " <a href=\"$self?page=$totalPages&$strGet\">Last</a> ";
		} else {
			$next = '<img src="images/right_o.gif" border="0">'; // we're on the last page, don't show 'next' link
			$last = ''; // nor 'last page' link
		}

		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;		
		
		$end   = min($totalPages, $end);
		
		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = " $page ";   // no need to create a link to current page
			} else {
				if ($page == 1) {
					$pagingLink[] = " <a href=\"$self?$strGet\">$page</a> ";
				} else {	
					$pagingLink[] = " <a href=\"$self?page=$page&$strGet\">$page</a> ";
				}	
			}
	
		}
		
		$pagingLink = implode(' | ', $pagingLink);
		
		// return the page navigation link
		//$pagingLink = $first . $prev . $pagingLink . $next . $last;
		$current_page=(isset($_GET['page'])) ? $_GET['page'] : 1;	//get the current page
		//$pagingLink = 'Page &nbsp;'.$current_page.'&nbsp; of '.$totalPages.'&nbsp;'.$first . $prev . $next .'//'. $last;
		$pagingLink = $prev .'&nbsp;'. $next;

	}
	
	return $pagingLink;
}

#==============================================END PHOTO PAGGING FUNCTION=========================
/*
	Create the paging links
*/
function getPagingNav($sql, $pageNum, $rowsPerPage, $queryString = '')
{
	$result  = mysql_query($sql) or die('Error, query failed. ' . mysql_error());
	$row     = mysql_fetch_array($result, MYSQL_ASSOC);
	$numrows = $row['numrows'];

	// how many pages we have when using paging?
	$maxPage = ceil($numrows/$rowsPerPage);

	$self = $_SERVER['PHP_SELF'];

	// creating 'previous' and 'next' link
	// plus 'first page' and 'last page' link

	// print 'previous' link only if we're not
	// on page one
	if ($pageNum > 1)
	{
		$page = $pageNum - 1;
		$prev = " <a href=\"$self?page=$page{$queryString}\">[Prev]</a> ";

		$first = " <a href=\"$self?page=1{$queryString}\">[First Page]</a> ";
	}
	else
	{
		$prev  = ' [Prev] ';       // we're on page one, don't enable 'previous' link
		$first = ' [First Page] '; // nor 'first page' link
	}

	// print 'next' link only if we're not
	// on the last page
	if ($pageNum < $maxPage)
	{
		$page = $pageNum + 1;
		$next = " <a href=\"$self?page=$page{$queryString}\">[Next]</a> ";

		$last = " <a href=\"$self?page=$maxPage{$queryString}{$queryString}\">[Last Page]</a> ";
	}
	else
	{
		$next = ' [Next] ';      // we're on the last page, don't enable 'next' link
		$last = ' [Last Page] '; // nor 'last page' link
	}

	// return the page navigation link
	return $first . $prev . " Showing page <strong>$pageNum</strong> of <strong>$maxPage</strong> pages " . $next . $last;
}
//end
//start user site pagging
function getUserPagingLink($sql, $itemPerPage = 10, $strGet = '')
{
	$result        = tep_db_query($sql);
	$pagingLink    = '';
	$totalResults  = tep_db_num_rows($result);
	$totalPages    = ceil($totalResults / $itemPerPage);
	
	// how many link pages to show
	$numLinks      = 10;

		
	// create the paging links only if we have more than one page of results
	
	if ($totalPages > 1) {
	
		$self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ;
		

		if (isset($_GET['page']) && (int)$_GET['page'] > 0) {
			$pageNumber = (int)$_GET['page'];
		} else {
			$pageNumber = 1;
		}
		
		// print 'previous' link only if we're not
		// on page one
		if ($pageNumber > 1) {
			$page = $pageNumber - 1;
			if ($page > 1) {
				$prev = "<a href=\"$self?page=$page&$strGet\" class=\"catAlphabetLnk\">&lt; Prev</a>";
			} else {
				$prev = "<a href=\"$self?$strGet\" class=\"catAlphabetLnk\">&lt; Prev</a>";
			}	
				
			$first = "<a href=\"$self?$strGet\" >First</a>";
		} else {
			$prev  = ''; // we're on page one, don't show 'previous' link
			$first = ''; // nor 'first page' link
		}
	
		// print 'next' link only if we're not
		// on the last page
		if ($pageNumber < $totalPages) {
			$page = $pageNumber + 1;
			$next = "<a href=\"$self?page=$page&$strGet\" class=\"catAlphabetLnk\">Next &gt;</a>";
			$last = "<a href=\"$self?page=$totalPages&$strGet\">Last</a>";
		} else {
			$next = ''; // we're on the last page, don't show 'next' link
			$last = ''; // nor 'last page' link
		}

		$start = $pageNumber - ($pageNumber % $numLinks) + 1;
		$end   = $start + $numLinks - 1;		
		
		$end   = min($totalPages, $end);
		
		$pagingLink = array();
		for($page = $start; $page <= $end; $page++)	{
			if ($page == $pageNumber) {
				$pagingLink[] = "<a href=\"#\" class=\"catAlphabetLnkActive\">$page</a>";   // no need to create a link to current page
			} else {
				if ($page == 1) {
					$pagingLink[] = "<a href=\"$self?$strGet\" class=\"catAlphabetLnk\">$page</a>";
				} else {	
					$pagingLink[] = "<a href=\"$self?page=$page&$strGet\" class=\"catAlphabetLnk\">$page</a>";
				}	
			}
			$moresing=($totalPages!=$page) ? '. . . .  '.$totalPages : '';
	
		}
		
		$pagingLink = implode(' ', $pagingLink);
		// return the page navigation link
		$pagingLink = $prev .'&nbsp;'. $pagingLink .$moresing.'&nbsp;'. $next;
	}
	
	return $pagingLink;
}

//end pagging

//generating random password here
function generatePassword($length = 8)
{

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; 
    
  // set up a counter
  $i = 0; 
    
  // add random characters to $password until $length is reached
  while ($i < $length) { 

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
        
    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}
//end generating random password
/*=========================================================================================
									MAIL FUNCTION 
===========================================================================================*/	
		function Email($to,$subject,$message,$from)
		{
			
			$header  = "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html; charset=iso-8859-1\r\n";
			$header .=	"From:".$from;
			$resmail=mail($to,$subject,$message,$header);
			if($resmail){
				$mailResult="sent";
				}
			else{
				$mailResult="Sorry mail could not send this time!";
			}
			return $mailResult;	
		}
//==============================END MAIL FUNCTION==========================================*/
/****************************************************************************************************
/*									FILE ATTATCHMENT												*/
//***************************************************************************************************
function fileattachMail($to,$subject,$message,$from,$files)
{
 //PHP Email Attachment v2
	
	$email_from = $from; // Who the email is from 
	$email_subject = $subject; // The Subject of the email 
	$email_message = $message; // Message that the email has in it 
	
	$email_to = $to; // Who the email is too 
	
	$headers = "From: ".$email_from; 
	
	
	
	$semi_rand = md5(time()); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 
		
	$headers .= "\nMIME-Version: 1.0\n" . 
				"Content-Type: multipart/mixed;\n" . 
				" boundary=\"{$mime_boundary}\""; 
	
	$email_message .= "This is a multi-part message in MIME format.\n\n" . 
					"--{$mime_boundary}\n" . 
					"Content-Type:text/html; charset=\"iso-8859-1\"\n" . 
				   "Content-Transfer-Encoding: 7bit\n\n" . 
	$email_message .= "\n\n"; 
	
	
	
	/* First File */ 
	//multiple files attachment
	$in=1;	//declraion of mutiple file name
	foreach($files as $path)
	{
		$extension 	  = strchr(str_replace('../file/','',$path),'.');
		$fileatt 	  = $path; // Path to the file                  
		$fileatt_type = "application/octet-stream"; // File Type 
		//$fileatt_name = 'myholyspirt'.$in.$extension; // Filename that will be used for the file as the attachment 
		$fileatt_name = $path;
		$file = fopen($fileatt,'rb'); 
		$data = fread($file,filesize($fileatt)); 
		fclose($file); 
		
		
		$data = chunk_split(base64_encode($data)); 
		
		$email_message .= "--{$mime_boundary}\n" . 
						  "Content-Type: {$fileatt_type};\n" . 
						  " name=\"{$fileatt_name}\"\n" . 
						  //"Content-Disposition: attachment;\n" . 
						  //" filename=\"{$fileatt_name}\"\n" . 
						  "Content-Transfer-Encoding: base64\n\n" . 
						 $data . "\n\n" . 
						  "--{$mime_boundary}\n"; 
		unset($data); 
		unset($file);
		unset($fileatt); 
		unset($fileatt_type); 
		unset($fileatt_name); 
		
			$in++;	//incrementing varibale
	}
	//end multiple file attachment
	// To add more files just copy the file section again, but make sure they are all one after the other! If they are not it will not work! 
	
	
	$ok = mail($email_to, $email_subject, $email_message, $headers); 
	
	if($ok) { 
		$msg='sent';
	} else { 
		 $msg="Sorry but the email could not be sent. Please go back and try again!"; 
	} 
	return $msg;
}	
//===============================================================================================================
/*
    Upload an image and return the uploaded image name 
*/

/*
	Create a thumbnail of $srcFile and save it to $destFile.
	The thumbnail will be $width pixels.
*/
/*
    Upload an image and return the uploaded image name 
*/
function uploadImage($inputName, $uploadDir,$maxWidth='',$smlWidth='',$miniWidth='',$url='',$size='')
{
    $image     = $_FILES[$inputName];
    $imagePath = '';
    
    // if a file is given
    if (trim($image['tmp_name']) != '') {
        // get the image extension
        $ext = substr(strrchr($image['name'], "."), 1); 

        // generate a random new file name to avoid name conflict
        $imagePath = md5(rand() * time()) . ".$ext";
        
		// check the image width. if it exceed the maximum
		// width we must resize it
		$size = getimagesize($image['tmp_name']);
		$filesize=getFileSize(filesize($image['tmp_name']));			//getting file size

		if ($filesize > $size) {
			header("location:".$url);
			exit(0);
		}
		if ($size[0] > 80) {
			if($maxWidth!=''){
			$imagePath = createThumbnail($image['tmp_name'], $uploadDir . $imagePath, $maxWidth);	//maxinum width of image
			}
			if($smlWidth!='')
			{
				$res=createThumbnail($image['tmp_name'], $uploadDir . 'small_'.$imagePath, $smlWidth);//thumbnail width of image
			}
			if($miniWidth!='')
			{	
				$res=createThumbnail($image['tmp_name'], $uploadDir . 'mini_'.$imagePath, $miniWidth);//thumbnail width of image
			}	
		} else {
			// move the image to category image directory
			// if fail set $imagePath to empty string
			if (!move_uploaded_file($image['tmp_name'], $uploadDir . $imagePath)) {
				$imagePath = '';
			}
		}	
    }

    
    return $imagePath;
}

function createThumbnail($srcFile, $destFile, $width, $quality = 75)
{
	$thumbnail = '';

	if (file_exists($srcFile)  && isset($destFile))
	{
		$size        = getimagesize($srcFile);
		$w           = number_format($width, 0, ',', '');
		$h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');	//default height

		$thumbnail =  copyImage($srcFile, $destFile, $w, $h, $quality);
	}

	// return the thumbnail file name on sucess or blank on fail
	return basename($thumbnail);
}

/*
	Copy an image to a destination file. The destination
	image size will be $w X $h pixels
*/
function copyImage($srcFile, $destFile, $w, $h, $quality = 75)
{
    $tmpSrc     = pathinfo(strtolower($srcFile));
    $tmpDest    = pathinfo(strtolower($destFile));
    $size       = getimagesize($srcFile);

    if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg")
    {
       $destFile  = substr_replace($destFile, 'jpg', -3);
       $dest      = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } elseif ($tmpDest['extension'] == "png") {
       $dest = imagecreatetruecolor($w, $h);
       imageantialias($dest, TRUE);
    } else {
      return false;
    }

    switch($size[2])
    {
       case 1:       //GIF
           $src = imagecreatefromgif($srcFile);
           break;
       case 2:       //JPEG
           $src = imagecreatefromjpeg($srcFile);
           break;
       case 3:       //PNG
           $src = imagecreatefrompng($srcFile);
           break;
       default:
           return false;
           break;
    }

    imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

    switch($size[2])
    {
       case 1:
       case 2:
           imagejpeg($dest,$destFile, $quality);
           break;
       case 3:
           imagepng($dest,$destFile);
    }
    return $destFile;

}

//end 
//functon get category name
function tep_category($pid=0)
{
	$sql="select * from ".TABLE_EXAMCATEGORY." order by id";
	$result=tep_db_query($sql);
	
	$select="<select name=\"parent\" class=\"inputBox\">";
	$select.="<option value=\"0\">Select exam</option>";
	while($row=tep_db_fetch_assoc($result))
	{
		$check=( ($pid!=0) && ($pid==$row['id'])) ? 'selected' : '';
		
			$select.="<option value=\"$row[id]\" $check >$row[exam_name]</option>";
	}
	
	$select.="</select>";
	
	return $select;
	
}
//end
/*cropping image*/
function cropImage($nw, $nh, $source, $stype, $dest) {
 
    $size = getimagesize($source);
    $w = $size[0];
    $h = $size[1];
 
    switch($stype) {
        case 'gif':
        $simg = imagecreatefromgif($source);
        break;
        case 'jpg':
        $simg = imagecreatefromjpeg($source);
        break;
        case 'png':
        $simg = imagecreatefrompng($source);
        break;
    }
 
    $dimg = imagecreatetruecolor($nw, $nh);
 
    $wm = $w/$nw;
    $hm = $h/$nh;
 
    $h_height = $nh/2;
    $w_height = $nw/2;
 
    if($w> $h) {
 
        $adjusted_width = $w / $hm;
        $half_width = $adjusted_width / 2;
        $int_width = $half_width - $w_height;
 
        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
 
    } elseif(($w <$h) || ($w == $h)) {
 
        $adjusted_height = $h / $wm;
        $half_height = $adjusted_height / 2;
        $int_height = $half_height - $h_height;
 
        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
 
    } else {
        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
    }
 
    imagejpeg($dimg,$dest,100);
}
//end croping image

/*
    Remove a category image
*/


/*
	Delete a category image where category = $catId
*/
function _deleteImage($catId)
{
    // we will return the status
    // whether the image deleted successfully
    $deleted = false;

	// get the image(s)
    $sql = "SELECT cat_image 
            FROM  picture_gallery
            WHERE cat_id = $catId";
	
	if (is_array($catId)) {
		$sql .= " IN (" . implode(',', $catId) . ")";
	} else {
		$sql .= " = $catId";
	}	

    $result = tep_db_query($sql);
    
    if (tep_db_num_rows($result)) {
        while ($row = tep_db_fetch_assoc($result)) {
	        // delete the image file
    	    $deleted = @unlink(UPLOAD_PROFILE_IMAGE . $row['cat_image']);
					   @unlink(UPLOAD_PROFILE_IMAGE . 'small_'.$row['cat_image']);
					   @unlink(UPLOAD_PROFILE_IMAGE . 'mini_'.$row['cat_image']);
					   @unlink(UPLOAD_PROFILE_IMAGE . $row['wallpaper']);
		}	
    }
    
    return $deleted;
}
//end

/*
	Delete a category image where category = $catId
*/
function _deleteCategoryImage($catId)
{
    // we will return the status
    // whether the image deleted successfully
    $deleted = false;

	// get the image(s)
    $sql = "SELECT cat_image 
            FROM  tbl_category
            WHERE cat_id ";
	
	if (is_array($catId)) {
		$sql .= " IN (" . implode(',', $catId) . ")";
	} else {
		$sql .= " = $catId";
	}	

    $result = tep_db_query($sql);
    
    if (tep_db_num_rows($result)) {
        while ($row = tep_db_fetch_assoc($result)) {
	        // delete the image file
    	    $deleted = @unlink(UPLOAD_CATEGORY_IMAGE . $row['cat_image']);
					   @unlink(UPLOAD_CATEGORY_IMAGE . 'small_'.$row['cat_image']);
					   @unlink(UPLOAD_CATEGORY_IMAGE . 'mini_'.$row['cat_image']);
		}	
    }
    
    return $deleted;
}
/*end*/

//functin to get the privious image
function getImage($catId)
{
	 $sql   = "SELECT cat_image 
            FROM  picture_gallery
            WHERE cat_id='$catId'";
	$result =tep_db_query($sql);
	$row	=tep_db_fetch_assoc($result);
	return $row['cat_image'];		
}
//end
//function to get more images
function getMoreImage($catId)
{
	 $sql   = "SELECT more_image 
            FROM projects
            WHERE id='$catId'";
	$result =tep_db_query($sql);
	$row	=tep_db_fetch_assoc($result);
	return $row['more_image'];		
}
//end
/*
	Delete a category image where category = $catId
*/
function _deleteImages($catId,$table,$column,$condition,$dir)
{
    // we will return the status
    // whether the image deleted successfully
    $deleted = false;

	// get the image(s)
    $sql = "SELECT $column 
            FROM  $table
            WHERE $condition ";
	
	if (is_array($catId)) {
		$sql .= " IN (" . implode(',', $catId) . ")";
	} else {
		$sql .= " = $catId";
	}	

    $result = tep_db_query($sql);
    
    if (tep_db_num_rows($result)) {
        while ($row = tep_db_fetch_assoc($result)) {
	        // delete the image file
    	    $deleted = @unlink($dir . $row[$column]);
					   @unlink($dir . 'small_'.$row[$column]);
					   @unlink($dir . 'mini_'.$row[$column]);
		}	
    }
    
    return $deleted;
}
//end



//functin to get the privious image
function getImages($catId,$table,$column,$condtion)
{
	 $sql   = "SELECT $column 
            FROM  $table
            WHERE $condtion='$catId'";
	$result =tep_db_query($sql);
	$row	=tep_db_fetch_assoc($result);
	return $row[$column];		
}
//end
//+++++++++++++++++++++++VIEW MORE PAGGING+++++++++++++++++++++++++++++
	function viewMore($string, $limit, $break=".", $pad="...") 
	{ 
	
		// return with no change if string is shorter than $limit  
		if(strlen($string) <= $limit) return $string; 
		// is $break present between $limit and the end of the string?  
		echo $breakpoint = strpos($string, $break, $limit);
		if(false !== ($breakpoint = strpos($string, $break, $limit))) 
			{ 
			if($breakpoint < strlen($string) - 1) 
				{ 
				$string = substr($string, 0, $breakpoint) . $pad;
				} 
			 } 
	 return $string; 
	 }
////+++++++++++++++++++++++END VIEW MORE PAGGING+++++++++++++++++++++++++++++
//getting severity name
function get_users()
{
	$sql="select lower(user_id) as title,id from ".TABLE_USERS." order by id";
	$result=tep_db_query($sql);
	
	$select="<select name=\"users\" class=\"inputBox\">";
	$select.="<option value=\"\">--select users--</option>";
	while($row=tep_db_fetch_assoc($result))
	{
		$select.="<option value=\"$row[id]\">$row[title]</option>";
	}
	$select.="</select>";
	
	return $select;
}
//end
//getting severity name
function editseverity($cid)
{

	$sql="select lower(title) as title from ".TABLE_SERVERITY." order by title";
	$result=tep_db_query($sql);
	
	$select="<select name=\"severity\" class=\"clsTextBox\">";
	while($row=tep_db_fetch_assoc($result))
	{	
		$check=($row['title']==strtolower($cid) ) ? 'selected' : '';
		
		$select.="<option value=\"$row[title]\" $check >$row[title]</option>";
	}
	$select.="</select>";
	
	return $select;
}
//end


//===================================================
//resize image
//===================================================
function Resize_File($file, $directory, $max_width = 0, $max_height = 0)
{
global $config;

$full_file = $directory.$file;

if (eregi("\.png$", $full_file))
{
$img = imagecreatefrompng($full_file);
}

if (eregi("\.(jpg|jpeg)$", $full_file))
{
$img = imagecreatefromjpeg($full_file);
}

if (eregi("\.gif$", $full_file))
{
$img = imagecreatefromgif($full_file);
}

$FullImage_width = imagesx($img);
$FullImage_height = imagesy($img);

if (isset($max_width) && isset($max_height) && $max_width != 0 && $max_height != 0)
{
$new_width = $max_width;
$new_height = $max_height;
}
elseif (isset($max_width) && $max_width != 0)
{
$new_width = $max_width;
$new_height = ((int)($new_width * $FullImage_height) / $FullImage_width);
}
elseif (isset($max_height) && $max_height != 0)
{
$new_height = $max_height;
$new_width = ((int)($new_height * $FullImage_width) / $FullImage_height);
}
else
{
$new_height = $FullImage_height;
$new_width = $FullImage_width;
}

$full_id = imagecreatetruecolor($new_width, $new_height);
imagecopyresampled($full_id, $img, 0, 0, 0, 0, $new_width, $new_height, $FullImage_width, $FullImage_height);


if (eregi("\.(jpg|jpeg)$", $full_file))
{
$full = imagejpeg($full_id, $full_file, 100);
}

if (eregi("\.png$", $full_file))
{
$full = imagepng($full_id, $full_file);
}

if (eregi("\.gif$", $full_file))
{
$full = imagegif($full_id, $full_file);
}

imagedestroy($full_id);
unset($max_width);
unset($max_height);
}
//======================================end resize image=============================

//=======================================start crop image=============================
//function imageCrop($nw, $nh, $source, $stype, $dest) {
// 
//    $size = getimagesize($source);
//    $w = $size[0];
//    $h = $size[1];
// 
//    switch($stype) {
//        case 'gif':
//        $simg = imagecreatefromgif($source);
//        break;
//        case 'jpg':
//        $simg = imagecreatefromjpeg($source);
//        break;
//        case 'png':
//        $simg = imagecreatefrompng($source);
//        break;
//    }
// 
//    $dimg = imagecreatetruecolor($nw, $nh);
// 
//    $wm = $w/$nw;
//    $hm = $h/$nh;
// 
//    $h_height = $nh/2;
//    $w_height = $nw/2;
// 
//    if($w> $h) {
// 
//        $adjusted_width = $w / $hm;
//        $half_width = $adjusted_width / 2;
//        $int_width = $half_width - $w_height;
// 
//        imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
// 
//    } elseif(($w <$h) || ($w == $h)) {
// 
//        $adjusted_height = $h / $wm;
//        $half_height = $adjusted_height / 2;
//        $int_height = $half_height - $h_height;
// 
//        imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
// 
//    } else {
//        imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
//    }
// 
//    imagejpeg($dimg,$dest,100);
//}
//=====================================end crop image===============================

#upload image for home page flash
function imageUpload($inputName, $uploadDir,$url='')
{
	$image     = $_FILES[$inputName];
	$file_name = '';
	
	// if a file is given
	// get the image extension
	$ext = substr(strrchr($image['name'], "."), 1); 
	
	// generate a random new file name to avoid name conflict
	
	
	// check the image width. if it exceed the maximum
	// width we must resize it
	$size = getimagesize($image['tmp_name']);
	if($ext!='')
	{
		$file_name = md5(rand() * time()) . ".$ext";
		if($ext =="jpeg" || $ext =="jpg" || $ext =="gif" || $ext =="flv")
		{
			move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
			chmod($uploadDir.$file_name,0644);
			
		}
		else{
			$file_name='';
			$err="Please upload spcified type type file!";
			header("location:".$url.'?err='.base64_encode($err));
			exit(0);
		}
	}
	else{
		$file_name='';
	}
return $file_name;
}	
#end upload image

#upload image for home page flash
function imageProfile($inputName, $uploadDir,$url='',$condition='')
{
	$image     = $_FILES[$inputName];
	$file_name = '';
	
	// if a file is given
	// get the image extension
	$ext = substr(strrchr($image['name'], "."), 1); 
	
	// generate a random new file name to avoid name conflict
	
	
	// check the image width. if it exceed the maximum
	// width we must resize it
	$size = getimagesize($image['tmp_name']);
	if($size[0]==210 && $size[1]==240)
	{
		$file_name = md5(rand() * time()) . ".$ext";
		if($ext =="jpeg" || $ext =="jpg" || $ext =="gif" || $ext =="flv")
		{
			move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
			chmod($uploadDir.$file_name,0644);
			
		}
		else{
			$file_name='';
			$err="Please upload spcified type type file!";
			header("location:".$url.'?err='.base64_encode($err));
			exit(0);
		}
	}
	if($size[0]>210 || $size[1]>240)
	{
			$file_name='';
			$err="Please upload spcified size file!";
			header("location:".$url.'?err='.base64_encode($err)."&".$condition);
			exit(0);
	}	
	return $file_name;
}	
#end upload image


#upload image for home page flash
function imageBanner($inputName, $uploadDir,$url='',$condition='',$width='',$height='')
{
	$image     = $_FILES[$inputName];
	$file_name = '';
	
	// if a file is given
	// get the image extension
	$ext = substr(strrchr($image['name'], "."), 1); 
	
	// generate a random new file name to avoid name conflict
	
	
	// check the image width. if it exceed the maximum
	// width we must resize it
	$size = getimagesize($image['tmp_name']);
	if($size[0]==$width && $size[1]==$height)
	{
		$file_name = md5(rand() * time()) . ".$ext";
		if($ext =="jpeg" || $ext =="jpg" || $ext =="gif" || $ext =="flv")
		{
			move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
			chmod($uploadDir.$file_name,0644);
			
		}
		else{
			$file_name='';
			$err="Please upload spcified type type file!";
			header("location:".$url.'?err='.base64_encode($err));
			exit(0);
		}
	}
	if($size[0]>$width || $size[1]>$height)
	{
			$file_name='';
			$err="Please upload spcified size file!";
			header("location:".$url.'?err='.base64_encode($err)."&".$condition);
			exit(0);
	}	
	return $file_name;
}	
#end upload image


//function to upload site logos
function uploadSiteLogo($inputName, $uploadDir,$url='',$condition='',$width='',$height='')
{
	$image     = $_FILES[$inputName];
	$file_name = '';
	
	// if a file is given
	// get the image extension
	$ext = substr(strrchr($image['name'], "."), 1); 
	
	// generate a random new file name to avoid name conflict
	
	
	// check the image width. if it exceed the maximum
	// width we must resize it
	$size = getimagesize($image['tmp_name']);
	if($size[0]==$width && $size[1]==$height && $ext!='')
	{
		$file_name = md5(rand() * time()) . ".$ext";
		if($ext =="jpeg" || $ext =="jpg" || $ext =="gif" || $ext =="flv")
		{
			move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
			chmod($uploadDir.$file_name,0644);
			
		}
		else{
			$err="Please upload spcified type type file!";
			header("location:".$url.'?err='.base64_encode($err));
			exit(0);
		}
	}else if($width=='' && $height=='' && $ext!=''){
		$file_name = md5(rand() * time()) . ".$ext";
		if($ext =="jpeg" || $ext =="jpg" || $ext =="gif" || $ext =="flv")
		{
			move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
			chmod($uploadDir.$file_name,0644);
			
		}
		else{
			$file_name='';
			$err="Please upload spcified type type file!";
			header("location:".$url.'?err='.base64_encode($err));
			exit(0);
		}
	}
	else if($size[0]>$width || $size[1]>$height && $ext!='')
	{
			$file_name='';
			$err="Please upload spcified size file!";
			header("location:".$url.'?err='.base64_encode($err)."&".$condition);
			exit(0);
	}	
	return $file_name;
}	
//end


#upload image for home page header
function imageHeader($inputName, $uploadDir,$url='')
{
	$image     = $_FILES[$inputName];
	$file_name = '';
	
	// if a file is given
	// get the image extension
	$ext = substr(strrchr($image['name'], "."), 1); 
	
	// generate a random new file name to avoid name conflict
	
	
	// check the image width. if it exceed the maximum
	// width we must resize it
	$size = getimagesize($image['tmp_name']);
	if($ext!=''){
		if($ext =="jpeg" || $ext =="jpg" || $ext =="gif" || $ext =="flv")
		{
			$file_name = md5(rand() * time()) . ".$ext";
			move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
			chmod($uploadDir.$file_name,0644);
			
		}
		else{
			$file_name='';
			$err="Please upload spcified type type file!";
			header("location:".$url.'?err='.base64_encode($err));
			exit(0);
		}
	}	
	return $file_name;
}	
#end upload image




#upload image for home page header
function uploadFile($inputName, $uploadDir)
{
	$image     = $_FILES[$inputName];
	$file_name = '';
	
	// if a file is given
	// get the image extension
	$ext = substr(strrchr($image['name'], "."), 1); 
	
	// generate a random new file name to avoid name conflict
	// check the image width. if it exceed the maximum
	// width we must resize it
	//$size = getimagesize($image['tmp_name']);
	if($ext!='' && ($ext=='docx' || $ext=='doc')){
		//$file_name = md5(rand() * time()) . ".$ext";
		$file_name=str_replace(' ','_',$image['name']);
		move_uploaded_file($image['tmp_name'],$uploadDir.$file_name);
		chmod($uploadDir.$file_name,0644);		
	}	
	return $file_name;
}	
#end upload image


//get the current page
function currentPage()
{
	list($root,$path,$folder,$page)=explode('/',PHP_SELF);
	return $page;
}
//end

//function to get file size
function getFileSize($size='')
{
	if($size!=''){ 
		  $filesize = $size;
		  $filesize = $filesize / 1024;
		  $filesize = round($filesize);
		  return $filesize;
	}	
}
//end
/*
	Generate combo box options containing the categories we have.
	if $catId is set then that category is selected
*/
function buildCategoryOptions($catId = 0)
{
	$sql = "SELECT cat_id, cat_parent_id, cat_name
			FROM tbl_category
			ORDER BY cat_id";
	$result = tep_db_query($sql) or die('Cannot get Product. ' . mysql_error());

	$categories = array();
	while($row = tep_db_fetch_array($result)) {
		list($id, $parentId, $name) = $row;
		
		$id=stripslashes($id);			//removing slashes
		$parentId=stripslashes($parentId);	//removing slashes
		$name=stripslashes($name);		//removing slashes
		
		if ($parentId == 0) {
			// we create a new array for each top level categories
			$categories[$id] = array('name' => $name, 'children' => array());
		} else {
			// the child categories are put int the parent category's array
			$categories[$parentId]['children'][] = array('id' => $id, 'name' => $name);
		}
	}

	// build combo box options
	$list = '';
	foreach ($categories as $key => $value) {
		$name     = $value['name'];
		$children = $value['children'];

		$list .= "<optgroup label=\"$name\">";

		foreach ($children as $child) {
			$list .= "<option value=\"{$child['id']}\"";
			if ($child['id'] == $catId) {
				$list.= " selected";
			}

			$list .= ">{$child['name']}</option>\r\n";
		}

		$list .= "</optgroup>";
	}

	return $list;
}

/*
	If you want to be able to add products to the first level category
	replace the above function with the one below
*/

//function get parent menus
function getMonth($mid='')
{
	$month=array('1'	=>'January',
				 '2'	=>'February',
				 '3'	=>'March',
				 '4'	=>'April',
				 '5'	=>'May',
				 '6'	=>'June',
				 '7'	=>'July',
				 '8'	=>'August',
				 '9'	=>'September',
				 '10'	=>'October',
				 '11'	=>'November',
				 '12'	=>'December'      
				 );
	$select='';
	$select='<select name="month" class="short">';
	$select.='<option value="">--select month--</option>';
		foreach($month as $key=> $val)
		{
			$check=($mid==$key) ? 'selected' : '';
			$select.="<option value=\"$key\" $check>".$val."</option>";
			
		}
	$select.="</select>";	
	
	return $select;
	
}
//end

//function check records
function getCheckRecord($table='',$condition='')
{
	$sql="select * from ".$table." where ".$condition;
	$result=tep_db_query($sql);
	$numrows=tep_db_num_rows($result);
	return $numrows;
}
//end

//get pages
function adPages($pname='')
{
	$pages=array('home page'	=>'Home Page',
				 'inner page'	=>'Inner Page'
				);
	$select='<select name="page_name" class="short">';
	$select.='<option value="">--select page--</option>';	
	foreach($pages as $key=>$val){
		$check=($pname==$key) ? 'selected' : '';
		$select.='<option value="'.$key.'" '.$check.'>'.$val.'</option>';
	}
	$select.='</select>';
	return $select;			
}
//end pages

//function for advertise valid
function validTime($id='')
{
	$select='<select name="valid">';
		$select.='<option value="">--select--</option>';
	for($i=1; $i<=12; $i++)
	{
		$check=($id==$i) ? 'selected' : '';
		$select.='<option value="'.$i.'"'.$check.'>'.$i.'</option>';	
	}
	$select.="</select>";
	
	return $select;
}
//end

//function to get countries
function country($iso_id='')
{
	$sql="select countries_name, countries_iso_code_3 from ".TABLE_COUNTRY." order by countries_name";
	$result=tep_db_query($sql);
	$select ='<select name="country" class="short" >';
	while($row=tep_db_fetch_assoc($result))
	{
		$select .='<option value='.$row['countries_iso_code_3'].'>'.$row['countries_name'].'</option>';
	}
	$select .='</select>';
	
	return $select;		
}
//end
//function get country
function getCountry($iso_id='')
{
	$sql="select * from ".TABLE_COUNTRY." where countries_iso_code_3='$iso_id'";
	$result=tep_db_query($sql);
	$row=tep_db_fetch_assoc($result);
	return $row;
}
//end
//function to get contact emails
function getContactEmails($site_id='')
{
	$sql="select email from ".TABLE_SITE_EMAILS." where site_id='$site_id' order by email";
	$result=tep_db_query($sql);
	$select ='<select name="mail_from">';
	while($row=tep_db_fetch_assoc($result))
	{
		$select .='<option value='.$row['email'].'>'.$row['email'].'</option>';
	}
	$select .='</select>';
	
	return $select;
}
//end

//function to get all requst advertise emails
function get_adv_emails()
{
	$sql="select email from ".TABLE_ADV_REQUEST." order by email";
	$result=tep_db_query($sql);
	$select ='<select name="mail_from" onChange="javascript:alert(0)">';
		$select.='<option value="">--select--';
	while($row=tep_db_fetch_assoc($result))
	{
		$select .='<option value='.$row['email'].'>'.$row['email'].'</option>';
	}
	$select .='</select>';
	
	return $select;
}
//end
//function to get banner details
function get_banner_info($id='')
{
	$sql_img="select  width, height from ".TABLE_IMAGE_SIZE." where id='$id'";
	$result=tep_db_query($sql_img);
	$row=tep_db_fetch_assoc($result);
	return $row;
}
//end

//function for visitor
function tep_views($view='',$id,$type='')
{
 	switch($view){	
		case 'show':
			$sql="select count(*) as views from ".TABLE_VIEWS." where vid='$id' and user_ip='".USER_IP."'"." and type='$type'";
			$result =tep_db_query($sql);
			$row	=tep_db_fetch_assoc($result);
			return $row['views'];
		break;
		default:
			$sql="select * from ".TABLE_VIEWS." where vid='$id' and user_ip='".USER_IP."'"." and type='$type'";
			$result=tep_db_query($sql);
			$numrows=tep_db_num_rows($result);
			if($numrows==0){
				//inserting records	
				$sql_data_array=array('vid'			=> $id,
									  'user_ip'		=> USER_IP,
									  'type'		=> $type					  			  
									  );			
				$res=tep_db_perform(TABLE_VIEWS, $sql_data_array);
			}
		break;
	}	
	
}
//end


//replaceing codes tabgs
function tep_code_string($string) {

	$tags=array('[cpp]'		=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="cpp">',
				'[/cpp]'	=> '</pre></div>',
				'[java]'	=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="java">',
				'[/java]'	=>'</pre></div>',
				'[php]'		=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="php">',
				'[/php]'	=>'</pre></div>',
				'[xml]'		=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="xml">',
				'[/xml]'	=>'</pre></div>',
				'[csharp]'	=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="csharp">',
				'[/csharp]'	=>'</pre></div>',
				'[vb]'		=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="vb">',
				'[/vb]'		=>'</pre></div>',
				'[jscript]'	=> '<div style="width:647px; overflow:scroll; overflow-x:hidden; border:1px #CCCCCC solid;">
<pre name="code" language="jscript">',
				'[/jscript]'=>'</pre></div>',
				'<pre><code>'=>'',
				'</code></pre>'=>''
			   );

  foreach($tags as $key =>$val){
     	
    	$string= str_replace($key , $val , $string);
	}	
	
	return $string;
}
//end

//eidt wmd editor
function edit_wmd($string)
{
	$tags=array(
				'<p>'		=> '',
				'</p>'		=> '',
				'<pre>' 	=> '',
				'</pre>'	=> '',	
				'<code>'	=> '',
				'</code>'	=> '',
				'<br />'		=> '',
				'<strong>'	=> '**',
				'</strong>'	=> '**',
				'<em>'		=> '*',
				'</em>'		=> '*',
				'<blockquote>'=> '> ',				
	);
	if(is_string($string)){

		foreach($tags as $key => $val){
			
			$string= str_replace($key , $val , $string);
			
		}
	}
	$string=stripslashes($string);
	return $string;
}
//end

//function pages
function tep_pages($page_id)
{
	$page=array('1'	=> 'Contact Us'
	);
	return $page[$page_id];
}
//end

//function to get rating
function tep_rating($type,$cid)
{
	$position=array('0' => array(-59,0),
					'1'	=> array(-47,-15),
					'2'	=> array(-47,0),
					'3'	=> array(-35,-15),
					'4'	=> array(-35,0),
					'5'	=> array(-23,-15),
					'6'	=> array(-23,0),
					'7'	=> array(-11,-15),
					'8'	=> array(-11,0),
					'9'	=> array(0,-15),
					'10'=> array(0,0)
					);				
	//end
	$sql="select round(format(avg(rate),1)) as rate from ".TABLE_RATING." where cid='$cid' and type='$type'";
	$result=tep_db_query($sql);
	$row=tep_db_fetch_assoc($result);
	$rating='';
	if($row['rate']!=''){
	
		foreach($position as $key => $val){
		
			if($row['rate']==$key){			
				
					$rating="<div class=\"smlStars\" style=\"background-position:$val[0]px $val[1]px;\"></div>";
			}
			
		}	
			
	}else{
					$rating='<div class="smlStars" style="background-position:-59px -0px;"></div>';
	}
	return $rating;
}
//end

###########################blogs functions#######################
function blog_category($cat_id='')
{
	$sql="select cat_id,lower(title) as title from ".TABLE_CATEGORY." order by title";
	$result=tep_db_query($sql);
	
	$select="<select name=\"category\" class=\"inputBox\">";
	$select.="<option value=\"\">--select category--</option>";
	while($row=tep_db_fetch_assoc($result))
	{
		$check=( ($cat_id!=0) && ($cat_id==$row['cat_id'])) ? 'selected' : '';
		$select.="<option value=\"$row[cat_id]\" $check >$row[title]</option>";
	}
	$select.="</select>";
	
	return $select;
}

//function get blog cateogry
function get_blog_cat($cat_id='')
{
	if((int)$cat_id==0){
		return 'Uncategorized';
	}else{
		
		$sql	="select title from ".TABLE_CATEGORY." where cat_id='$cat_id'";
		$result =tep_db_query($sql);	
		$row	=tep_db_fetch_assoc($result);		
		return $row['title'];
	}
}
//end
//no of comments
function no_of_comments($cid='')
{
	$sql="select count(*) as comm from ".TABLE_COMMENTS." where cid='$cid'";
	$result =tep_db_query($sql);
	$row	=tep_db_fetch_assoc($result);
	return $row['comm'];
}
//end
##########################end blog functions#####################


//function view comment type wise
function comment_type()
{
	$comment_type=array('activities'	=> 'Activities',
						 'advisories'	=> 'Advisories',
						 'blog'			=> 'Blog',
						 'news'			=> 'News',
						 'projects'  	=> 'Projects',
						 'tutorial'		=> 'Tutorial'
						);
	return $comment_type;					
}
//end

//getting random ids
function tep_random_id($table,$id,$key)
{
	$sql="select $id as id from ".$table." order by id";
	$result=tep_db_query($sql);
	while($row=tep_db_fetch_assoc($result))
	{
		extract($row);
		$getID[]=$id;
		
	}
	if(count($getID)>4){
		srand((float) microtime() * 10000000);
		$rand_keys = array_rand($getID, 2);
		$tid=$getID[$rand_keys[$key]];			//getting random testing monial id
	}else{
		$tid='';
	}	
	return $tid;
}
//end

//getting header image path
function get_header($type='')
{
	switch($type)
	{
		case 'home':
			$sql="select * from ".TABLE_HEADER." where home_page='yes'";
		break;
		
		case 'inner':
			$sql="select * from ".TABLE_HEADER." where inner_page='yes'";
		break;
	}
		$result =tep_db_query($sql);
		$row	=tep_db_fetch_assoc($result);
		return $row['file'];
	
}
//end
//functon get menus
function tep_news($cat_id=0)
{
	$sql="select * from ".TABLE_CATEGORY." order by cat_id";
	$result=tep_db_query($sql);
	
	$select="<select name=\"cats\" class=\"inputBox\">";
	$select.="<option value=\"0\">--Select Category--</option>";
	while($row=tep_db_fetch_assoc($result))
	{
		$check=( ($cat_id!=0) && ($cat_id==$row['cat_id'])) ? 'selected' : '';
		
			$select.="<option value=\"$row[cat_id]\" $check >$row[cat_name]</option>";
	}
	$select.="</select>";
	
	return $select;
	
}
//end
//functon get menus
function tep_menus($parent_id=0)
{
	$sql="select * from ".TABLE_MENUS." order by parent_id";
	$result=tep_db_query($sql);
	
	$select="<select name=\"parent\" class=\"inputBox\">";
	$select.="<option value=\"0\">--select menu--</option>";
	while($row=tep_db_fetch_assoc($result))
	{
		$check=( ($parent_id!=0) && ($parent_id==$row['id'])) ? 'selected' : '';
		
			$select.="<option value=\"$row[id]\" $check >$row[name]</option>";
	}
	$select.="</select>";
	
	return $select;
	
}
//end

//function get inner page banner
function tep_inner_banner($pid='')
{
	$sql	="select title, image from ".TABLE_INNER_BANNER." where pid='$pid' order by rand() limit 1";
	$result =tep_db_query($sql);
	$row	=tep_db_fetch_assoc($result);
	if($row['image']!=''){
		$image='images/banner_images/'.$row['image'];
	}else{
		$image='images/h-partners.jpg';
	}
	echo "<img src=\"$image\" alt=\"$row[title]\">";
}
//end


//get parent
function get_parent($table,$condition)
{
	$sql	="select * from $table where $condition";
	$result =tep_db_query($sql);	
	$row 	=tep_db_fetch_assoc($result);
	return $row;
}
//end

//function get children
function get_children($cat_id)
{
    $sql = "SELECT id ".
           "FROM ".TABLE_MENUS.
           " WHERE parent_id='$cat_id'";
    $result = tep_db_query($sql);
    
	$cat = array();
	if (tep_db_num_rows($result) > 0) {
		while ($row = tep_db_fetch_row($result)) {
			$cat[] = $row[0];
			
			// call this function again to find the children
			$cat  = array_merge($cat, get_children($row[0]));
		}
    }

    return $cat;
}
//end
//function generate order number
function orderNumber($table,$order_no='',$id)
{
	$sql="SELECT * FROM $table";
	$result=tep_db_query($sql);
	$numrows=tep_db_num_rows($result);
	$select="<select name='order_no' onchange=".'"'."location.href='".PHP_SELF."?action=ordnum&num='+this.value+'&id='+$id".'"'.">";
	$select.="<option value=\"\">--select--</option>";
	if($numrows>(int)0)
	{
		for($i=1;$i<=$numrows;$i++)
		{
			$check=($i==$order_no) ? 'selected' : '';
			$select.="<option value=\"$i\" $check >$i</option>";
		}
	}
	else
	{
			$check=(1==$order_no) ? 'selected' : '';
			$select.="<option value=\"1\" $check>1</option>";
	}
	$select.="</select>";
	
	return $select;		
}
//end
?>