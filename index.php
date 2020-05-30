<?php

session_start();
require_once("assets/class.user.php");
$login = new USER();

if($login->is_loggedin()!="")
{
  $login->redirect('assets/my-ebook.php');  
}

if(isset($_POST['btn-login']))
{
  $login_uname = strip_tags($_POST['txt_uname_email']);
  $login_umail1 = strip_tags($_POST['txt_uname_email']);
  $login_upass1 = strip_tags($_POST['txt_password']);
 
  if($login->doLogin($login_uname,$login_umail1,$login_upass1))
  {         
    $login->redirect('assets/my-ebook.php');  
  }
  else
  {
    $login_error = "The Username or Password is incorrect!";
  }
  ?> 
   <?php
}

if(isset($_POST['btn-signup']))
{
  $uname = strip_tags($_POST['txt_uname']);
  $firstname = strip_tags($_POST['txt_fname']);
  $lastname = strip_tags($_POST['txt_lname']);
  $upass1 = strip_tags($_POST['txt_upass1']);
  $upass2 = strip_tags($_POST['txt_upass2']); 
  $umail1 = strip_tags($_POST['txt_umail1']);
  $umail2 = strip_tags($_POST['txt_umail2']);

  if(!filter_var($umail1, FILTER_VALIDATE_EMAIL)) {
    $registration_error = 'Please enter a valid email address!';
  }
  else if($umail1!= $umail2) {
    $registration_error = "Email Address does not match!";
  }
  else if(strlen($upass1) < 6){
    $registration_error = "Password must be atleast 6 characters"; 
  }
  else if($upass1!= $upass2) {
    $registration_error = "Password does not match!";
  }
  else
  {
    try
    {
      $stmt = $login->runQuery("SELECT user_name, user_email FROM users WHERE user_name=:uname OR user_email=:umail1");
      $stmt->execute(array(':uname'=>$uname, ':umail1'=>$umail1));
      $row=$stmt->fetch(PDO::FETCH_ASSOC);

      if($row['user_name']==$uname) {
        $registration_error = "Sorry, Username is already taken!";
      }
      else if($row['user_email']==$umail1) {
        $registration_error = "Sorry, Email Address is already taken!";
      }

      else
      {
        if($login->register_account($uname,$firstname,$lastname,$upass1,$umail1)){  
          $success_registration = "TRUE";
        }
      }
    }
    catch(PDOException $e)
    {
      echo $e->getMessage();
    }
  } 
}

?>
<link href="css/index.css" rel="stylesheet" type="text/css" />
<script src="js/jquery.min.js"></script>

<head>
<link rel="icon" type="image/png" href="http://localhost/ebookmaker/favicon.ico" />
</head>


<div class="index-page">
  <div class="form" id="form">
    <div id="register">
      <div id="register-alert">
        <div id ="register-error">
        <?php
          if(isset($registration_error))
          {
            ?>
            <script>
             var register_error = document.getElementById("register-error");
             register_error.style.display = "block";
            </script>
            <div class="alert alert-danger">
               <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $registration_error; ?>
            </div>
        <?php
          }
          else if(isset($success_registration))
          {
         ?>
            <script>
             var register_success = document.getElementById("register-error");
             register_success.style.display = "block";
             register_success.style.background = "#93ffb2";
             register_success.style.color = "#24a048";

            </script>
            <div class="alert alert-info">
              <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Registered
            </div>
        <?php
          }
        ?>
        </div><!-- end of 'register-error' -->
      </div><!-- end of 'register-alert' -->

    <form method="post" class="register-form" id="register-form">
      <input type="text" name="txt_uname" placeholder="Username" value="<?php if(isset($registration_error)){echo $uname;}?>" required/>
      <input type="text" name="txt_fname" placeholder="First Name" value="<?php if(isset($registration_error)){echo $firstname;}?>" required/>
      <input type="text" name="txt_lname" placeholder="Last Name" value="<?php if(isset($registration_error)){echo $lastname;}?>" required/>
      <input type="password" name="txt_upass1" placeholder="Password" value="<?php if(isset($registration_error)){echo $upass1;}?>" required/>
      <input type="password" name="txt_upass2" placeholder="Confirm Password" value="<?php if(isset($registration_error)){echo $upass2;}?>" required/>
      <input type="text" name="txt_umail1" placeholder="Email Address" value="<?php if(isset($registration_error)){echo $umail1;}?>" required/>
      <input type="text" name="txt_umail2" placeholder="Confirm Email Address" value="<?php if(isset($registration_error)){echo $umail2;}?>" required/>
      <button type="submit" name="btn-signup" id="btn-signup">register</button>
      <p class="message">Already registered? <a id="signin" href="#">Sign In</a></p>
    </form>
    </div><!-- end of 'register' -->

    <div id="login">
      <div id="login-alert">
        <div id="login-error">
        <?php
          if(isset($login_error))
          {
          ?>
          <script>
             var login_error = document.getElementById("login-error");
             login_error.style.display = "block";
          </script>
            <div class="alert alert-danger">
               <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $login_error; ?>
            </div>
        <?php
          }
           else if(isset($success_registration))
          {
         ?>
              <script>
               var login_error = document.getElementById("login-error");
               login_error.style.display = "block";
               login_error.style.background = "#93ffb2";
               login_error.style.color = "#24a048";
              </script>
            <div class="alert alert-info">
              <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Registered
            </div>
        <?php
          }
        ?>
      </div>
    </div><!-- end of 'login-alert' -->

    <form method ="post" class="login-form" id="login-form">
      <input type="text" name="txt_uname_email" placeholder="Username or Email" required/>
      <input type="password" name="txt_password" placeholder="Password" required/>
      <button type="submit" name="btn-login" id="btn-login">login</button>
      <p class="message">Not registered? <a id="signup" href="#">Create an account</a></p>
      <p class="message">Forgot Password? <a id="forgot" href="#">Ask for reset</a></p>
    </form>
    </div><!-- end of 'login' -->

    <div id ="reset">
      <form method ="post" class="reset-form" id="reset-form">
        <input type="text" name="txt_uname_email" placeholder="Email Address" required/>
        <button type="submit" name="btn-reset" id="btn-reset">Forgot Password</button>
        <p class="message"><a id="signin" href="#">Back to Sign in</a></p>
      </form>
    </div><!-- end of 'reset' -->
  </div> <!-- end of 'form' -->
</div> <!-- end of 'index-page' -->


<?php
  if(!isset($registration_error) OR isset($success_registration))
  {
?>
   <script>
       var register = document.getElementById("register");
       var login = document.getElementById("login");
       var reset = document.getElementById("reset");

       register.style.display = "none";
       reset.style.display = "none";
       login.style.display = "block";
   </script>
<?php
  }
  else{
?>
  <script>
     var register = document.getElementById("register");
     var login = document.getElementById("login");
     var reset = document.getElementById("reset");

     reset.style.display = "none";
     register.style.display = "block";
     login.style.display = "none";
 </script>
<?php
    }
?>

<script>
  var register = document.getElementById("register");
  var login = document.getElementById("login");
  var reset = document.getElementById("reset");

    $('#signup').click(function(){
    $('.login-form').hide();
    $('.register-form').show();

       register.style.display = "block";
       login.style.display = "none";
  });

    $('#signin').click(function(){
    $('.login-form').show();
    $('.register-form').hide();

     reset.style.display = "none";
     register.style.display = "none";
     login.style.display = "block";
  });

    $('#forgot').click(function(){
    $('.login-form').hide();
    $('.reset-form').show();

       reset.style.display = "block";
       login.style.display = "none";
  });
</script>

