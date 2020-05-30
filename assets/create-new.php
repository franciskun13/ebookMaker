<?php
include("header.php");
include("dbconfig(ebooks).php");

$create = new USER();

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
  $subject_specify = strip_tags($_POST['txt_subject_specify']);

  if ($subject == $subject25)
  {
  	$new_subject = $subject_specify;
  }
  else
  {
  	$new_subject = $subject;
  }

  $publisher = strip_tags($_POST['txt_publisher']);
  $date = date();
  $identifiere = strip_tags($_POST['txt_identifiere']);
  $language = strip_tags($_POST['txt_language']);
  $relation = strip_tags($_POST['txt_relation']);
  $rights = strip_tags($_POST['txt_rights']);

  if (isset($_FILES["cover_image"]["name"])) 
  {
  $cover_image_tmp= $_FILES["cover_image"]["tmp_name"];
  $cover_image_name= $_FILES["cover_image"]["name"];
  $type = $_FILES['cover_image']['type'];
  $extension = explode('.', $cover_image_name);
  $extension = end($extension);
  }
  
	    try
	    {
	    	  $stmt = $create->runQuery("SELECT ebook_identifiere FROM ebooks WHERE ebook_identifiere=:ebook_identifiere");
		      $stmt->execute(array(':ebook_identifiere'=>$identifiere));
		      $row=$stmt->fetch(PDO::FETCH_ASSOC);

		    if($row['ebook_identifiere']==$identifiere) 
		    {
		        $create_error = "Sorry, Identifiere is already taken!";
		     
		    }
	    	else if($cover_image_tmp != "")
	    	{
		  		if (exif_imagetype($cover_image_tmp) != IMAGETYPE_GIF AND exif_imagetype($cover_image_tmp) != IMAGETYPE_JPEG AND
		  			exif_imagetype($cover_image_tmp) != IMAGETYPE_PNG) 
		  		{
			  	$create_error = "Invalid File:Please Upload Image File.";
			  	// $create_error .= $extension;
			  	}
			  	else
			  	{

				  	if($create->create_ebook($title,$creator,$new_subject,$publisher,$date,$identifiere,$language,$relation,$rights,$cover_image_name))
		          	{
		              $success_create = "TRUE";

			            mkdir("html/".$identifiere, 0700);
			            mkdir("html/".$identifiere."/images", 0700);
			            move_uploaded_file($cover_image_tmp, getcwd()."/html/"
			            	.$identifiere."/images/".$cover_image_name);
	        			
	        			$sql = "SELECT ebook_id FROM ebooks WHERE ebook_identifiere = ".$identifiere;
						$result = $conn_ebooks->query($sql);
						$row = $result->fetch_assoc();

						$ebook_id = $row['ebook_id'];

						//query for page count
						$result_count = $conn_ebooks->query("SELECT COUNT(page_id) AS count FROM pages");
						$row_count = $result_count->fetch_assoc();
						$page_count = $row_count['count'];

						if ($page_count == 0)
						{
							$page_id = "1";
						}
						else if($page_count != 0) 
						{
							//query for max page_id
							$result_max = $conn_ebooks->query("SELECT MAX(page_id) AS max FROM pages");
							$row_max = $result_max->fetch_assoc();
							$page_max = $row_max['max'];

							$page_max++;
							$page_id = $page_max;
							
						}

						$page_name ="Untitled";
						$page_content="";

						echo $page_id;


						$create->create_page($page_id,$ebook_id,$page_name,$page_content);

	        			echo "<script type=\"text/javascript\">
	                        window.location = \"edit-content.php?id=$ebook_id\"
	                      </script>";
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
					$create_error = "Invalid File:Please Upload Image File.";
					// $create_error .= $extension;
				}
				else
			  	{

				  	if($create->create_ebook($title,$creator,$new_subject,$publisher,$date,$identifiere,$language,$relation,$rights,$cover_image_name))
		          	{
		              $success_create = "TRUE";

			            mkdir("html/".$identifiere, 0700);
			            mkdir("html/".$identifiere."/images", 0700);
			            move_uploaded_file($cover_image_tmp, getcwd()."/html/"
			            	.$identifiere."/images/".$cover_image_name);
	        			
	        			$sql = "SELECT ebook_id FROM ebooks WHERE ebook_identifiere = ".$identifiere;
						$result = $conn_ebooks->query($sql);
						$row = $result->fetch_assoc();

						$ebook_id = $row['ebook_id'];

						//query for page count
						$result_count = $conn_ebooks->query("SELECT COUNT(page_id) AS count FROM pages");
						$row_count = $result_count->fetch_assoc();
						$page_count = $row_count['count'];

						if ($page_count == 0)
						{
							$page_id = "1";
						}
						else if($page_count != 0) 
						{
							//query for max page_id
							$result_max = $conn_ebooks->query("SELECT MAX(page_id) AS max FROM pages");
							$row_max = $result_max->fetch_assoc();
							$page_max = $row_max['max'];

							$page_max++;
							$page_id = $page_max;
							
						}

						$page_name ="Untitled";
						$page_content="";

						echo $page_id;


						$create->create_page($page_id,$ebook_id,$page_name,$page_content);

	        			echo "<script type=\"text/javascript\">
	                        window.location = \"edit-content.php?id=$ebook_id\"
	                      </script>";
		            }
		        }

			}
 
		}
	    catch(PDOException $e)
	    {
	      echo $e->getMessage();
	    }
 }


?>

<!-- Link Background Script -->
<script>
var create_new = document.getElementById("create_new");
	create_new.style.background ="#1b6059";
</script>

<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../css/create-new.css" rel="stylesheet" type="text/css" />

<body>
<div id="content">
	<div id="form">
			<div id="edit">
				<center><h2>Create New eBook</h2><br></center>
	      <div id="create-alert">
	        <div id ="create-error">
	        <?php
	          if(isset($create_error))
	          {
	            ?>
	            <script>
	             var create_error = document.getElementById("create-error");
	             create_error.style.display = "block";
	            </script>
	               <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $create_error; ?>
	            
	        <?php
	          }
	          else if(isset($success_create))
	          {
	        ?>
	            <script>
	             var edit_success = document.getElementById("create-error");
	             edit_success.style.display = "block";
	             edit_success.style.background = "#93ffb2";
	             edit_success.style.color = "#24a048";
	            </script>

	              <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully Created
	           
	        <?php
	          }
	        ?>
	        </div><!-- end of 'create-error' -->
	      </div><!-- end of 'create-alert' -->

	    <form method="post" enctype="multipart/form-data" class="create-form" id="create-form">
	      <b>Title</b>
	      <input type="text" name="txt_title" value="<?php if(isset($create_error)){echo $title;} else{} ?>" required/>

	  	  <b>Creator</b>
	      <input type="text" name="txt_creator" value="<?php if(isset($create_error)){echo $creator;} else{}?>" required/>

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
<input type="text" name="txt_subject_specify" value="<?php if(isset($create_error)){echo $identifiere;} else{}?>" />
</div>
</div>

		  <b>Publisher</b>
	      <input type="text" name="txt_publisher"  value="<?php if(isset($create_error)){echo $publisher;} else{}?>"/>

	      <b>Identifiere</b>
	      <input type="text" name="txt_identifiere" onkeypress="return isIdentifiere(event)" value="<?php if(isset($create_error)){echo $identifiere;} else{}?>" required/>

	      <b>Language</b>
	      <input type="text" name="txt_language"  value="<?php if(isset($create_error)){echo $language;} else{}?>"/>

	      <b>Relation</b>
	      <input type="text" name="txt_relation"  value="<?php if(isset($create_error)){echo $relation;} else{}?>"/>


	      <b>Rights</b>
	      <input type="text" name="txt_rights"  value="<?php if(isset($create_error)){echo $rights;} else{}?>"/>

	      <b>Cover Image (Optional)</b>
	      <input type="file" name="cover_image" id="cover_image"/>

	      <br><br><br>
	      <button type="submit" name="btn-save" id="btn-save">save and continue</button>
	    </form>
	      <button type="submit" onclick="cancel()" name="btn-cancel" id="btn-cancel">cancel</button>
	    </div><!-- end of 'create' -->
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

if (isset($create_error))
{
	?>
	<script type="text/javascript">
	var e = document.getElementById ("select_subject");
	var subject = "<?php echo $subject; ?>";
	var strUser = e.options [e.selectedIndex].value;

	e.value = subject;
	</script>
	<?php
}

?>

<?php
include("footer.php");
?>
