<?php
include("header.php");

$edit = new USER();

$user_id = $_SESSION['user_session']['user_id'];
$user_name = $_SESSION['user_session']['user_name'];
$user_firstname = $_SESSION['user_session']['user_firstname'];
$user_lastname = $_SESSION['user_session']['user_lastname'];
$user_email = $_SESSION['user_session']['user_email'];
$user_pass = $_SESSION['user_session']['user_pass'];

if(isset($_POST['btn-info']))
{

  $uname = strip_tags($_POST['txt_uname']);
  $firstname = strip_tags($_POST['txt_fname']);
  $lastname = strip_tags($_POST['txt_lname']);
  $umail = strip_tags($_POST['txt_umail']);

	    try
	    {
          if ($edit->edit_account_info($uname,$firstname,$lastname,$umail)){
              $success_edit = "TRUE";
              $_SESSION['user_session']['user_name'] =  $uname;
      		    $_SESSION['user_session']['user_firstname'] =  $firstname;
      		    $_SESSION['user_session']['user_lastname'] = $lastname;
      		    $_SESSION['user_session']['user_email'] = $umail;
            }
        
	    }
	  
	    catch(PDOException $e)
	    {
	      echo $e->getMessage();
	    }
	   
 }

 if(isset($_POST['btn-pass']))
{

  $upass = strip_tags($_POST['txt_upass']);
  $newpass1 = strip_tags($_POST['txt_newpass1']);
  $newpass2 = strip_tags($_POST['txt_newpass2']);

  $new_password = password_hash($newpass1, PASSWORD_DEFAULT);

  if(password_verify($upass, $user_pass)) {
      try
      {
        if(strlen($newpass1) < 6){
          $password_error = "Password must be atleast 6 characters"; 
        }
        else if($newpass1!= $newpass2) {
          $password_error = "Password does not match!";
        }
        else
        {  
          if ($edit->edit_account_pass($new_password)){
              $success_edit = "TRUE";
              $_SESSION['user_session']['user_pass'] = $new_password;
            }
        }
      }
    
      catch(PDOException $e)
      {
        echo $e->getMessage();
      }
    } 
  
  else{

    $password_error = 'Current Password is incorrect';
  }
 }

?>
<!-- Link Background Script -->
<script>
var account_settings = document.getElementById("account_settings");
	account_settings.style.background = "#1b6059";
</script>
<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../css/edit-account.css" rel="stylesheet" type="text/css" />

<div id="content">
	<div id="form">
		<div id="edit">
			<center><h2>Edit Account</h2><br></center>
      <div id="edit-alert">
        <div id ="edit-error">
        <?php
          if(isset($edit_error))
          {
            ?>
            <script>
             var edit_error = document.getElementById("edit-error");
             edit_error.style.display = "block";
            </script>
               <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $edit_error; ?>
            
        <?php
          }
          else if(isset($password_error))
          {
            ?>
            <script>
             var edit_error = document.getElementById("edit-error");
             edit_error.style.display = "block";
            </script>
               <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $password_error; ?>
            
        <?php
          }
          else if(isset($success_edit))
          {
        ?>
            <script>
             var edit_success = document.getElementById("edit-error");
             edit_success.style.display = "block";
             edit_success.style.background = "#93ffb2";
             edit_success.style.color = "#24a048";

            </script>
              <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Edited
           
        <?php
          }
        ?>
        </div><!-- end of 'edit-error' -->
      </div><!-- end of 'edit-alert' -->

    <form method="post" class="edit-form1" id="edit-form1">
      <b>User Name</b>
      <input type="text" name="txt_uname" value="<?php if(isset($edit_error)){echo $uname;} else{echo $_SESSION['user_session']['user_name'];} ?>" required/>

  	  <b>First Name</b>
      <input type="text" name="txt_fname" value="<?php if(isset($edit_error)){echo $firstname;} else{echo $_SESSION['user_session']['user_firstname'];}?>" required/>

      <b>Last Name</b>
      <input type="text" name="txt_lname"  value="<?php if(isset($edit_error)){echo $lastname;} else{echo $_SESSION['user_session']['user_lastname'];}?>" required/>
      
      <b>Email Address</b>
      <input type="text" name="txt_umail"  value="<?php if(isset($edit_error)){echo $umail;} else{echo $_SESSION['user_session']['user_email'];}?>" required/>

      <button type="submit" name="btn-info" id="btn-info">Update Information</button>
    </form>

      <br><br><br>

    <form method="post" class="edit-form2" id="edit-form2">

      <input type="password" name="txt_upass" placeholder="Current Password"  required/>

      <input type="password" name="txt_newpass1" placeholder="New Password"  required/>

      <input type="password" name="txt_newpass2" placeholder="Confirm New Password" required/>

      <button type="submit" name="btn-pass" id="btn-pass">Update Password</button>
    </form>
      <!-- <button type="submit" onclick="cancel()" name="btn-cancel" id="btn-cancel">cancel</button> -->
    </div><!-- end of 'edit' -->
	</div><!-- end of 'form' -->
</div><!-- end of 'content' -->

<script>
    function cancel()
    {
      window.location.href ="my-ebook.php";
  	}
</script>

<?php
include("footer.php");
?>

