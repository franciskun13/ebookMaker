<?php
include ("session.php");
?>

<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />

<style>

body{

 font-family: calibri;

}

#header {
  position: static;
  background-color: #FFFFFF;
  padding:20px;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 2px 2px 0 rgba(0, 0, 0, 0.24);
}
#link{
	float:right;
  margin-top: 40px;
}
#link a:hover{
  background: #26897e;
  text-decoration: none;
}
#link a:focus{
  background: #1b6059;
}
#link a {
  font-family: Arial;	
  outline: 0;
  background: #34baac;
  text-transform: uppercase;
  /*text-decoration: none;*/
  font-weight: bold;
  border: 0;
  width:100px;
  height:50px;
  margin: 3px;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
  border-radius: 5px;
}

#header img {

  cursor: pointer;

}

</style>

<head>
<link rel="icon" type="image/png" href="http://localhost/ebookmaker/favicon.ico" />
</head>

<div id="header">
	<img src="../css/logo/ebookmaker2.png" onclick="image_link()" width="300" alt="ebookmaker"/>

	<div id="link">
    <a id="create_new" href="create-new.php">Create New</a>
    <a id="my_ebook" href="my-ebook.php">My Ebook</a>
    <a id="account_settings" href="edit-account.php">Edit Account</a>
		<a id="signout" href="sign-out.php?sign-out=true">Sign Out</a>
	</div>
</div>

<script>
  function image_link()

  {
    window.location.href="my-ebook.php";
  }
</script>

