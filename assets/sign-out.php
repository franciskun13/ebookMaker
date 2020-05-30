<?php
	require_once('session.php');
	require_once('class.user.php');
	$user_logout = new USER();
	
	if($user_logout->is_loggedin()!="")
	{
		if ($_SESSION['user_session']['user_type'] == "ADMIN"){

                $user_logout->redirect('admin');
        }

  		else{
                $user_logout->redirect('my-ebook.php');
        }
		
	}
	if(isset($_GET['sign-out']) && $_GET['sign-out']=="true")
	{
		$user_logout->doLogout();

		// if LIVE
		// $user_logout->redirect('../../');

		// if LOCAL
		   $user_logout->redirect('../index.php');

	}
