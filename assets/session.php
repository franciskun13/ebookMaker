<?php
	session_start();
	include ('class.user.php');
	$session = new USER();
	// if user session is not active(not loggedin) this page will help 'admin.php and profile.php' to redirect to login page
	// put this file within secured pages that users (users can't access without login)
	
	if(!$session->is_loggedin())
	{
		// session no set redirects to login page

		// if LIVE
		//$session->redirect('../../');

		// if LOCAL
		  $session->redirect('../index.php');
	}
	
?>