<?php
include("header.php");
include("dbconfig(ebooks).php");


$update = new USER();

$id = $_GET['id'];

$result = $conn_ebooks->query("SELECT * FROM ebooks WHERE ebook_id=$id");
$row = $result->fetch_assoc();

 $_SESSION['user_session']['ebook_id'] = $row["ebook_id"];
 $_SESSION['user_session']['ebook_title'] = $row["ebook_title"];
 $_SESSION['user_session']['ebook_creator'] = $row["ebook_creator"];
 $_SESSION['user_session']['ebook_subject'] = $row["ebook_subject"];
 $_SESSION['user_session']['ebook_identifiere'] = $row["ebook_identifiere"];
 $_SESSION['user_session']['ebook_language'] = $row["ebook_language"];
 $_SESSION['user_session']['ebook_rights'] = $row["ebook_rights"];
 $_SESSION['user_session']['ebook_cover_image'] = $row["ebook_cover_image"];

 $ebook_identifiere = $row["ebook_identifiere"];
 $cover_image = $_SESSION['user_session']['ebook_cover_image'];

 $_SESSION['user_session']['ebook_cover_image_path'] = "html/".$ebook_identifiere."/images/".$cover_image;
 $cover_image_path = $_SESSION['user_session']['ebook_cover_image_path'];

 if ($cover_image == "")
 {
  $cover_image = "No cover image is uploaded.";
  $cover_image_path = "";
 }

$subject1 ="Action and Adventures";
$subject2 ="Anthology";
$subject3 ="Autobiography";
$subject4 ="Children's Literature";
$subject5 ="Comics and Graphic Novels";
$subject6 ="Cookbooks";
$subject7 ="Dictionary";
$subject8 ="Diaries/Journals";
$subject9 ="Drama";
$subject10 ="Encyclopedias";
$subject11 ="Fantasy";
$subject12 ="History";
$subject13 ="Horror and Mystery";
$subject14 ="How-to Guide";
$subject15 ="Math";
$subject16 ="Poetry";
$subject17 ="Prayer Books";
$subject18 ="Religion and Spirituality";
$subject19 ="Romance";
$subject20 ="Science Fiction";
$subject21 ="Science";
$subject22 ="Self-Help";
$subject23 ="Travel and Leisure";
$subject24 ="Young Adult";
$subject25 ="Others (Please Specify)";
$new_subject = "";

if(isset($_POST['btn-save']))
{
  $title = strip_tags($_POST['txt_title']);
  $creator = strip_tags($_POST['txt_creator']);
  $subject = strip_tags($_POST['select_subject']);
  $subject_specify = strip_tags($_POST['txt_specify']);

  if ($subject == $subject25)
  {
    $new_subject = $subject_specify;
  }
  else
  {
    $new_subject = $subject;
  }

  $publisher = strip_tags($_POST['txt_publisher']);
  $date = strip_tags($_POST['txt_date']);
  $identifiere = strip_tags($_POST['txt_identifiere']);
  $language = strip_tags($_POST['txt_language']);
  $relation = strip_tags($_POST['txt_relation']);
  $rights = strip_tags($_POST['txt_rights']);

  if(isset($_FILES["cover_image"]["name"])) 
  {

  $cover_image_tmp= $_FILES["cover_image"]["tmp_name"];
  $cover_image_name= $_FILES["cover_image"]["name"];
  $type = $_FILES['cover_image']['type'];
  $extension = explode('.', $cover_image_name);
  $extension = end($extension);
  }
  else
  {
    if ($_SESSION['user_session']['ebook_cover_image'] != "")
    {
    $cover_image_name = $_SESSION['user_session']['ebook_cover_image'];
    }
    else{
    $cover_image_name = "";  
    }
  }
  
      try
      {
        $stmt = $update->runQuery("SELECT ebook_identifiere FROM ebooks WHERE ebook_identifiere=:ebook_identifiere");
          $stmt->execute(array(':ebook_identifiere'=>$identifiere));
          $row=$stmt->fetch(PDO::FETCH_ASSOC);

        // if($row['ebook_identifiere']==$identifiere) 
        // {
        //     $update_error = "Sorry, Identifiere is already taken!";
        // }
        if($cover_image_tmp != "")
        {
          if (exif_imagetype($cover_image_tmp) != IMAGETYPE_GIF AND exif_imagetype($cover_image_tmp) != IMAGETYPE_JPEG AND
            exif_imagetype($cover_image_tmp) != IMAGETYPE_PNG) 
          {
          $update_error = "Invalid File:Please Upload Image File.";
          // $update_error .= $extension;
          }
          else
          {

            if ($update->update_ebook($title,$creator,$new_subject,$publisher,$date,$identifiere,$language,$relation,$rights,$cover_image_name))
                {
                  $success_update = "eBook Info Successfully Updated";

                  function recurse_copy($src, $dst){
                      /* Returns false if src doesn't exist */
                      $dir = @opendir($src);

                      /* Make destination directory. False on failure */
                      if (!file_exists($dst)) @mkdir($dst);

                      /* Recursively copy */
                      while (false !== ($file = readdir($dir))) {

                          if (( $file != '.' ) && ( $file != '..' )) {
                             if ( is_dir($src . '/' . $file) ) recurse_copy($src . '/' . $file, $dst . '/' . $file); 
                             else copy($src . '/' . $file, $dst . '/' . $file);
                         } 

                      }
                     closedir($dir); 
                  }
                  function removeDirectory($src) {
                      $files = glob($src . '/*');
                      foreach ($files as $file) {
                        is_dir($file) ? removeDirectory($file) : unlink($file);
                      }
                      rmdir($src);
                      return;
                    }

                    /* Source directory (can be an FTP address) */
                    $src = getcwd()."/html/".$_SESSION['user_session']['ebook_identifiere'];

                    /* Full path to the destination directory */
                    $dst = getcwd()."/html/".$identifiere;

                    /* Usage */
                    recurse_copy($src, $dst);
                    move_uploaded_file($cover_image_tmp, getcwd()."/html/"
                    .$identifiere."/images/".$cover_image_name);

                    if ($_SESSION['user_session']['ebook_identifiere'] != $identifiere)
                    {
                      removeDirectory($src);
                    }

                   $_SESSION['user_session']['ebook_title'] = $title;
                   $_SESSION['user_session']['ebook_creator'] = $creator;
                   $_SESSION['user_session']['ebook_subject'] = $new_subject;
                   $_SESSION['user_session']['ebook_identifiere'] = $identifiere;
                   $_SESSION['user_session']['ebook_language'] = $language;
                   $_SESSION['user_session']['ebook_rights'] = $rights;
                  
                   $_SESSION['user_session']['ebook_cover_image'] = $cover_image_name;
                   $_SESSION['user_session']['ebook_cover_image_path'] = getcwd()."/html/".$identifiere."/images/".$cover_image_name;
                   $_SESSION['user_session']['success'] = "eBook Info Successfully Updated";
                   header("Location:edit-ebook-info.php?id=$id");

                }
            }
      }
      else if ($cover_image_tmp == "") 
      {

        if($extension != "gif" AND
           $extension != "jpg" AND
           $extension != "png" AND
           $extension != "")
        {
          $update_error = "Invalid File:Please Upload Image File.";
          // $update_error .= $extension;
        }
        else
        {
          if ($update->update_ebook($title,$creator,$new_subject,$publisher,$date,$identifiere,$language,$relation,$rights,$cover_image_name))
                {
                  $success_update = "eBook Info Successfully Updated";

                   function recurse_copy($src, $dst) 
                  {

                      /* Returns false if src doesn't exist */
                      $dir = @opendir($src);

                      /* Make destination directory. False on failure */
                      if (!file_exists($dst)) @mkdir($dst);

                      /* Recursively copy */
                      while (false !== ($file = readdir($dir))) {

                          if (( $file != '.' ) && ( $file != '..' )) {
                             if ( is_dir($src . '/' . $file) ) recurse_copy($src . '/' . $file, $dst . '/' . $file); 
                             else copy($src . '/' . $file, $dst . '/' . $file);
                         } 

                      }

                     closedir($dir); 
                    }
                    function removeDirectory($src) {
                      $files = glob($src . '/*');
                      foreach ($files as $file) {
                        is_dir($file) ? removeDirectory($file) : unlink($file);
                      }
                      rmdir($src);
                      return;
                    }

                    /* Source directory (can be an FTP address) */
                    $src = getcwd()."/html/".$_SESSION['user_session']['ebook_identifiere'];

                    /* Full path to the destination directory */
                    $dst = getcwd()."/html/".$identifiere;

                    /* Usage */
                    recurse_copy($src, $dst);
                    move_uploaded_file($cover_image_tmp, getcwd()."/html/"
                    .$identifiere."/images/".$cover_image_name);

                    if ($_SESSION['user_session']['ebook_identifiere'] != $identifiere)
                    {
                      removeDirectory($src);
                    }

                   $_SESSION['user_session']['ebook_title'] = $title;
                   $_SESSION['user_session']['ebook_creator'] = $creator;
                   $_SESSION['user_session']['ebook_subject'] = $new_subject;
                   $_SESSION['user_session']['ebook_identifiere'] = $identifiere;
                   $_SESSION['user_session']['ebook_language'] = $language;
                   $_SESSION['user_session']['ebook_rights'] = $rights;
                   $_SESSION['user_session']['ebook_cover_image'] = $cover_image_name;
                   
                   $_SESSION['user_session']['ebook_cover_image_path'] = getcwd()."/html/".$identifiere."/images/".$cover_image_name;
                   $_SESSION['user_session']['success'] = "eBook Info Successfully Updated";
                   header("Location:edit-ebook-info.php?id=$id");
                }
            }
      }
 
    }
      catch(PDOException $e)
      {
        echo $e->getMessage();
      }
}
if(isset($_POST['delete']))
{
  $success_update = "Cover Image Successfully Deleted";
  $cover_image = "";
  if ($update->delete_cover($cover_image))            
    {
      $success_update = "Cover Image Successfully Deleted";
      

      $path ="html/".$_SESSION['user_session']['ebook_identifiere']."/cover/".$_SESSION['user_session']['ebook_cover_image'];

      unlink($path);
      
      $_SESSION['user_session']['ebook_cover_image'] = "";
      $cover_image_path = "";
      $cover_image = "No cover image is uploaded.";
    }
}


?>

<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../css/edit-ebook-info.css" rel="stylesheet" type="text/css" />

<body>
<div id="content">
  <div id="form">
      <div id="edit">
        <center><h2>Edit eBook Information</h2><br></center>
        <div id="update-alert">
          <div id ="update-error">
          <?php
            if(isset($update_error))
            {
              ?>
              <script>
               var update_error = document.getElementById("update-error");
               update_error.style.display = "block";
              </script>
                 <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $update_error; ?>
              
          <?php
            }
            else if(isset($success_update))
            {
          ?>
              <script>
               var edit_success = document.getElementById("update-error");
               edit_success.style.display = "block";
               edit_success.style.background = "#93ffb2";
               edit_success.style.color = "#24a048";
              </script>

                <i class="glyphicon glyphicon-log-in"></i> &nbsp; <?php echo $success_update; ?>
             
          <?php
            }
             else if(isset($_SESSION['user_session']['success']))
                {
              ?>
                  <script>
                   var edit_success = document.getElementById("update-error");
                   edit_success.style.display = "block";
                   edit_success.style.background = "#93ffb2";
                   edit_success.style.color = "#24a048";
                  </script>

                    <i class="glyphicon glyphicon-log-in"></i> &nbsp; <?php echo $_SESSION['user_session']['success']; ?>

                 
              <?php
                $_SESSION['user_session']['success'] = null;
                }
              ?>

          </div><!-- end of 'update-error' -->
        </div><!-- end of 'update-alert' -->

      <form method="post" enctype="multipart/form-data" class="update-form" 
       id="update-form">
        <b>Title</b>
        <input type="text" name="txt_title" value="<?php if(isset($update_error)){echo $title;} else{echo $_SESSION['user_session']['ebook_title'];} ?>" required/>

        <b>Creator</b>
        <input type="text" name="txt_creator" value="<?php if(isset($update_error)){echo $creator;} else{echo $_SESSION['user_session']['ebook_creator'];}?>" required/>

        <b>Subject</b>

<script type="text/javascript">
  
  function specify()
  {
  var e = document.getElementById ("select_subject");
  var txt_subject_specify = document.getElementById ("txt_subject_specify");
  var select_subject = document.getElementById ("select_subject");
  var subject25 = "<?php echo $subject25; ?>";
  var strUser = e.options [e.selectedIndex].value;

  if (strUser == subject25)
  {
    txt_subject_specify.style.display = "block";
  }
  else
  {
    txt_subject_specify.style.display = "none";
  }
  
  }

</script>

<div class="select-style">

<select id="select_subject" name="select_subject" onchange="specify()">

<option id="1" value="<?php echo $subject1 ?>"><?php echo $subject1 ?></option>
<option id="2" value="<?php echo $subject2 ?>"><?php echo $subject2 ?></option>
<option id="3" value="<?php echo $subject3 ?>"><?php echo $subject3 ?></option>
<option id="4" value="<?php echo $subject4 ?>"><?php echo $subject4 ?></option>
<option id="5" value="<?php echo $subject5 ?>"><?php echo $subject5 ?></option>
<option id="6" value="<?php echo $subject6 ?>"><?php echo $subject6 ?></option>
<option id="7" value="<?php echo $subject7 ?>"><?php echo $subject7 ?></option>
<option id="8" value="<?php echo $subject8 ?>"><?php echo $subject8 ?></option>
<option id="9" value="<?php echo $subject9 ?>"><?php echo $subject9 ?></option>
<option id="10" value="<?php echo $subject10 ?>"><?php echo $subject10 ?></option>
<option id="11" value="<?php echo $subject11 ?>"><?php echo $subject11 ?></option>
<option id="12" value="<?php echo $subject12 ?>"><?php echo $subject12 ?></option>
<option id="13" value="<?php echo $subject13 ?>"><?php echo $subject13 ?></option>
<option id="14" value="<?php echo $subject14 ?>"><?php echo $subject14 ?></option>
<option id="15" value="<?php echo $subject15 ?>"><?php echo $subject15 ?></option>
<option id="16" value="<?php echo $subject16 ?>"><?php echo $subject16 ?></option>
<option id="17" value="<?php echo $subject17 ?>"><?php echo $subject17 ?></option>
<option id="18" value="<?php echo $subject18 ?>"><?php echo $subject18 ?></option>
<option id="19" value="<?php echo $subject19 ?>"><?php echo $subject19 ?></option>
<option id="20" value="<?php echo $subject20 ?>"><?php echo $subject20 ?></option>
<option id="21" value="<?php echo $subject21 ?>"><?php echo $subject21 ?></option>
<option id="22" value="<?php echo $subject22 ?>"><?php echo $subject22 ?></option>
<option id="23" value="<?php echo $subject23 ?>"><?php echo $subject23 ?></option>
<option id="24" value="<?php echo $subject24 ?>"><?php echo $subject24 ?></option>
<option id="25" value="<?php echo $subject25 ?>"><?php echo $subject25 ?></option>

</select>

<div id="txt_subject_specify">
<input type="text" id="txt_specify" name="txt_specify" value="<?php if(isset($update_error)){echo $subject_specify;} else{}?>" />
</div>
</div>
        <b>Publisher</b>
        <input type="text" name="txt_publisher"  value="<?php if(isset($update_error)){echo $publisher;} else{echo $_SESSION['user_session']['ebook_publisher'];}?>"/>

        <b>Date</b>
        <input type="text" style="background: #f2f2f2;" name="txt_identifiere" value="<?php if(isset($update_error)){echo $date;} else{echo $_SESSION['user_session']['ebook_date'];}?>" required readonly/>

        <b>Identifiere</b>
        <input type="text" style="background: #f2f2f2;" name="txt_identifiere" onkeypress="return isIdentifiere(event)" value="<?php if(isset($update_error)){echo $identifiere;} else{echo $_SESSION['user_session']['ebook_identifiere'];}?>" required readonly/>

        <b>Language</b>
        <input type="text" name="txt_language"  value="<?php if(isset($update_error)){echo $language;} else{echo $_SESSION['user_session']['ebook_language'];}?>"/>

        <b>Relation</b>
        <input type="text" name="txt_relation"  value="<?php if(isset($update_error)){echo $relation;} else{echo $_SESSION['user_session']['ebook_relation'];}?>"/>

        <b>Rights</b>
        <input type="text" name="txt_rights"  value="<?php if(isset($update_error)){echo $rights;} else{echo $_SESSION['user_session']['ebook_rights'];}?>"/>
        
        <b><p>Cover Image</p></b>

        <div id="input-cover-div">
        <p><?php echo $cover_image;?></p>
        <?php
        if ($cover_image_path == "")
        {
         echo "<input type=\"file\" name=\"cover_image\" id=\"cover_image\"/>";
         echo "</center>";
        }
        else
        {
         echo"<div style=\"margin-left:200px;\">";
         echo"<input type=\"submit\" name=\"delete\" id=\"delete\" class=\"btn btn-danger\" value=\"X\"><br></div>";
         echo "<img src=\"".$cover_image_path."\" width=\"50%\"/>"; 
        }
        ?> 
        </div>

        <br><br><br>
        <button type="submit" name="btn-save" id="btn-save">Update Information</button>
      </form>
        <button type="submit" onclick="cancel()" name="btn-cancel" id="btn-cancel">cancel</button>
      </div><!-- end of 'update' -->
    </div><!-- end of 'form' -->
</div><!-- end of 'content' -->

</body>

<script type="text/javascript">

function cancel()
{
window.location.href ="my-ebook.php";
}
</script>

<!-- Identifiere Script -->
<script language="Javascript">

function isIdentifiere(evt1)
{
var identifiere = (evt1.which) ? evt1.which : event.keyCode
if (identifiere != 45 && identifiere > 31 
&& (identifiere < 48 || identifiere > 57))
return false;

return true;
}
</script>

<?php

if (isset($update_error))
{
  $_SESSION['user_session']['ebook_subject'] = $subject;
  $_SESSION['user_session']['subject_specify'] = $subject_specify;

  ?>
  <script type="text/javascript">
  var e = document.getElementById ("select_subject");
  var subject25 = "<?php echo $subject25;?>";
  var txt_specify = document.getElementById ("txt_specify");
  var subject = "<?php echo $_SESSION['user_session']['ebook_subject']?>";
  var strUser = e.options [e.selectedIndex].value;

  e.value = subject;
  // txt_subject_specify.style.display = "block";

  if (e.value == "")
  {
    txt_subject_specify.style.display = "hidden";
    e.value = subject25;
    txt_specify.value = subject;
  }
  if (e.value == subject25)
  {
    txt_subject_specify.style.display = "block";
  }
  </script>
  <?php
}
else
{
 ?>
  <script type="text/javascript">
  var e = document.getElementById ("select_subject");
  var subject25 = "<?php echo $subject25;?>";
  var txt_specify = document.getElementById ("txt_specify");
  var subject = "<?php echo $_SESSION['user_session']['ebook_subject']?>";
  var strUser = e.options [e.selectedIndex].value;

  e.value = subject;

  if (e.value == "")
  {
    txt_subject_specify.style.display = "block";
    e.value = subject25;
    txt_specify.value = subject;
  }

  </script>
  <?php
}

?>

<?php
include("footer.php");
?>
