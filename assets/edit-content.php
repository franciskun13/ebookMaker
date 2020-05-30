<?php
include ("header.php");
include ('dbconfig(ebooks).php');

$create_session = new USER();


//getting id of the ebook from url
$id = $_GET['id'];
$p = $_GET['p'];

//pagination numbering
if (isset($_GET["page"])) 
{
 $page = $_GET["page"];
} 
else
{ 
 $page=1;
};

$start_from = ($page-1) * 1;

include("sidebar.php");

//query for max page_id
$result_max = $conn_ebooks->query("SELECT MAX(page_id) AS max FROM pages");
$row_max = $result_max->fetch_assoc();
$_SESSION['user_session']['page-max'] = $row_max['max'];
$page_max = $_SESSION['user_session']['page-max'];

//query for ebook
$result_ebook = $conn_ebooks->query("SELECT * FROM ebooks WHERE ebook_id=".$id);
//query for page

$result_page = $conn_ebooks->query("SELECT * FROM pages WHERE ebook_id = $id  ORDER BY page_id ASC LIMIT $start_from, 1");

//query for page count
$result_count = $conn_ebooks->query("SELECT COUNT(page_id) AS count FROM pages WHERE ebook_id=".$id);

$ebook_id = $id;

//rows from queries
$row_ebook = $result_ebook->fetch_assoc();
$row_count = $result_count->fetch_assoc();

//setting session variable from result of the queries

//page details

while($row_page = $result_page->fetch_assoc()) 
{
$_SESSION['user_session']['page-id'] = $row_page["page_id"];
$_SESSION['user_session']['page-name'] = $row_page["page_name"];
$_SESSION['user_session']['page-content'] = $row_page["page_content"];
//ebook details
$_SESSION['user_session']['ebook-identifier'] = $row_ebook["ebook_identifier"];
$_SESSION['user_session']['ebook-title'] = $row_ebook["ebook_title"];
$_SESSION['user_session']['ebook-path'] ="html/".$ebook_identifier;
//page count
$_SESSION['user_session']['page-count'] = $row_count['count'];

//variable declaration from session variables
$ebook_title = $_SESSION['user_session']['ebook-title'];
$ebook_identifier = $_SESSION['user_session']['ebook-identifier'];
$ebook_path = $_SESSION['user_session']['ebook-path'];
$page_count = $_SESSION['user_session']['page-count'];

$page_id = $_SESSION['user_session']['page-id'];
$page_name = $_SESSION['user_session']['page-name'];
$page_content = $_SESSION['user_session']['page-content'];


$previous_button = "<input type=\"submit\" name=\"edit_previous\" id=\"edit_previous\" class=\"btn\" value=\"Edit Previous Page\">";
$next_button = "<input type=\"submit\" name=\"edit_next\" id=\"edit_next\" class=\"btn\" value=\"Edit Next Page\">";
$delete_button = "<input type=\"submit\" name=\"delete\" id=\"delete\" class=\"btn btn-danger\" value=\"Delete Page\">";

if ($page_count == 0 OR $page_count == 1)
{
  $page_count = 1;

  $previous_button = "<input type=\"submit\" name=\"edit_previous\" id=\"edit_previous\" class=\"btn\" value=\"Edit Previous Page\" disabled>";

  $next_button = "<input type=\"submit\" name=\"edit_next\" id=\"edit_next\" class=\"btn\" value=\"Edit Next Page\" disabled>";

  $delete_button = "<input type=\"submit\" name=\"delete\" id=\"delete\" class=\"btn btn-danger\" value=\"Delete Page\" disabled>"; 
}

if ($page == 1){
  $previous_button = "<input type=\"submit\" name=\"edit_previous\" id=\"edit_previous\" class=\"btn\" value=\"Edit Previous Page\" disabled>";
}
if ($page == $page_count)
{
  $next_button = "<input type=\"submit\" name=\"edit_next\" id=\"edit_next\" class=\"btn\" value=\"Edit Next Page\" disabled>";
}

//create new page
if(isset($_POST['new']))
{
    try
      {
        $page_id = $page_max + 1;
        $page_name ="Untitled";
        $page_content="";

        $create_session->create_page($page_id,$ebook_id,$page_name,$page_content);
      }
    catch(PDOException $e)
      {
        echo $e->getMessage();
      }

      $page_count++;

        echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id&page=$page_count\"
              </script>";
}

//previous page
if(isset($_POST['edit_previous']))
{
  $page--;

  echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id&page=$page\"
              </script>";
}

//next new page
if(isset($_POST['edit_next']))
{
  $page++;

  echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id&page=$page\"
              </script>";
}

//save page
if(isset($_POST['save']))
{
  $page_name = strip_tags($_POST['page_name']);
  $page_content = $_POST['chapter-content'];

  $session_link = $_SESSION['user_session']['ebook-identifier'];

  $old_string = "http://localhost/eBookMaker/assets/html/$session_link/images/";
  $new_string = "../images/";

  $copy_page = str_replace($old_string,$new_string, $page_content);

  // $copy_page = str_replace("world","Peter","Hello world!");

  // $page_file = fopen(, "w") or die("Unable to open file!");

  try
    {

    if ($page_name == "")
    {
      echo "<script type=\"text/javascript\">
              window.alert(\"Saving Failed! Page name is empty.\");
            </script>";
      echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id&page=$page\"
              </script>";
    }
    else
    {
      if($create_session->update_page($page_id,$ebook_id,$page_name,$page_content))
        {  
          $success_creation = "TRUE";

          echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id&page=$page\"
              </script>";

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
 
  try
    {
        if($create_session->delete_page($page_id))
        {  
          $success_creation = "TRUE";

          if ($page != 1)
          {
            $page--;
          }

          echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id&page=$page\"
              </script>";
        }
    }

  catch(PDOException $e)
  {
    echo $e->getMessage();
  }
  
}



echo "
<html>
<head>

<meta charset=\"utf-8\">
<meta name=\"viewport\" content=\"width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0\"/>

<link href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />

<link rel=\"stylesheet\" href=\"../css/codemirror.min.css\">

<link href=\"../css/froala_editor.pkgd.min.css\" rel=\"stylesheet\" type=\"text/css\" />

<link href=\"../css/froala_style.min.css\" rel=\"stylesheet\" type=\"text/css\" />



<link href=\"../css/edit-content.css\" rel=\"stylesheet\" type=\"text/css\" />

<link href=\"../css/dark.min.css\" rel=\"stylesheet\" type=\"text/css\" />


</head>

<body>

<div id=\"body\">
<div id=\"form-box\">

	
		<h4><b>Editing: $ebook_title</b></h4>
	<div id=\"buttons\">

  <form method=\"post\">

		<input type=\"submit\" name=\"save\" id=\"save\" class=\"btn btn-primary\" value=\"Save Changes\" onclick=\"passData()\">

		<div id=\"download-button\">
                  <a class=\"btn btn-success\">Download File</a>
                      <div id=\"download-content\">
                  <a href=\"download-epub.php?l=2&as=zip&id=$ebook_id\">As ZIP</a>
                  <a href=\"download-epub.php?as=epub&id=$ebook_id\">As EPUB</a>
                      </div>
    </div>
    <br>
		<input type=\"submit\" name=\"new\" id=\"new\" class=\"btn btn-primary\" value=\"Create New Page\">
		$previous_button
    $next_button
		
		Editing Page $page of $page_count <br><br>

		<b>Page Name:</b><br>
		<input type=\"text\" class=\"input-xxlarge\" id=\"page_name\" name=\"page_name\" value=\"$page_name\" style=\"height: 32px; width: 100%;\">

    <div id=\"div-content\" style=\"display:none\">
    <textarea id=\"chapter-content\" name=\"chapter-content\"></textarea>
    </div>

</form>
	</div> <!-- end of 'button' -->
	
	<div id=\"wysiwyg\">
		<textarea id=\"froala-editor\" name=\"froala-editor\">$page_content</textarea>
	</div> <!-- end of 'wysiwyg' -->

	<div id=\"bottom\">
  <form method=\"post\">
		  $delete_button
  </form>
		<p>Pages:</p>
   ";
 }

        $sql = "SELECT COUNT(page_id) AS total FROM pages WHERE ebook_id = '".$id."'";
        $result = $conn_ebooks->query($sql);
        $row = $result->fetch_assoc();
        $count = $page_count;

        if ($count == 0)
        {
          $count = 1;
        }

        $total_pages = $count; 
        // calculate total pages with results 
        echo "<div id=\"pagination\">";
        for ($i=1; $i<=$total_pages; $i++) 
        {  
        // print links for all pages
        $link_id = "#".$page; 
        $link_url = "edit-content.php?id=$id&page=".$i;
              echo "<a id=\"$i\" href='".$link_url."'";
              if ($i==$page)  echo "class='curPage'";
              echo ">".$i."</a> ";       
        };
        echo "</div>";
    ?>
	</div> <!-- end of 'bottom' -->

</div> <!-- end of 'form-box' -->
  </div> <!-- end of 'button' -->

  




<!-- Include external JS libs. -->
<script src="../js/jquery-2.2.4.js"></script>

<script type="text/javascript" src="../js/jquery.min.js"></script>

<script type="../js/codemirror.min.js"></script>

<script type="text/javascript" src="../js/xml.min.js"></script>

<!-- Include Editor JS files. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script>

<script type="text/javascript" src="../js/file.min.js"></script>
<script type="text/javascript" src="../js/image.min.js"></script>



<!-- Froala-Editor Script -->

<script>
$(function() {
  $('textarea#froala-editor').froalaEditor({

  	 heightMin: 800,
     heightMax: 1300,
     inlineMode: false,
     imageDefaultAlign: 'center',
     imageDefaultDisplay: 'break-text',
     toolbarSticky: false,
     
     fontFamily: {
      "Arial,sans-serif": 'Arial',
      "Helvetica, sans-serif": 'Helvetica',
      "Times New Roman,serif": 'Times New Roman',
      "Minion Pro,serif": 'Minion Pro Regular',
      "Montserrat,sans-serif": 'Montserrat',
      "Verdana,sans-serif": 'Verdana'
     },
     fontFamilySelection: false
    
  })
});
</script>

<!-- File and Image Upload Script -->

<script>
  $(function() {
    $('.selector')
      .froalaEditor({
        // Set the image upload parameter.
        imageUploadParam: 'file',
        
        // Set the image upload URL.
        imageUploadURL: "http://localhost/eBookMaker/assets/image-upload.php",

        // Additional upload params.
        imageUploadParams: {id: 'my_editor'},

         // Set the file upload parameter.
        fileUploadParam: 'file',
 
        // Set the file upload URL.
        fileUploadURL: "http://localhost/eBookMaker/assets/image-upload.php",
 
        // Additional upload params.
        fileUploadParams: {id: 'my_editor'},
 
        // Set request type.
        fileUploadMethod: 'POST',
 
        // Set max file size to 20MB.
        fileMaxSize: 20 * 1024 * 1024,
 
        // Allow to upload any file.
        fileAllowedTypes: ['*']
      })
      .on('editable.imageError', function (e, editor, error) {
        // Custom error message returned from the server.
        if (error.code == 0) { ... }
        // Bad link.
        else if (error.code == 1) { ... }
        // No link in upload response.
        else if (error.code == 2) { ... }
        // Error during image upload.
        else if (error.code == 3) { ... }
        // Parsing response failed.
        else if (error.code == 4) { ... }
        // Image too text-large.
        else if (error.code == 5) { ... }
        // Invalid image type.
        else if (error.code == 6) { ... }
        // Image can be uploaded only to same domain in IE 8 and IE 9.
        else if (error.code == 7) { ... }
      });
  });
</script>

<!-- Pagination Script -->
<script>
var page = "<?php echo $page;?>";
var side_page = "<?php echo "a".$page;?>";
var id = document.getElementById(page);
var id1 = document.getElementById(side_page);

 id.style.fontWeight ="bold";
 id.style.color= "red";
 id.style.fontSize ="16";

 id1.style.color= "red";
 id1.style.fontWeight ="bold";
 
</script>

<script>

    function passData() {
    var id = "<?php echo $id ?>";
    var html = $('#froala-editor').froalaEditor('html.get');
    var dataString = 'html-data=' + html;

    $("#chapter-content").html(html);


    // AJAX code to submit form.
    // $.ajax({
    // type: "POST",
    // url: "pass-data.php",
    // data: dataString,
    // cache: false,
    // success: function(url) {
     

    // },
    // error: function(err) {
    // alert(err);
    // }
    // });
    // return false;
    }
</script>

</body>
<?php include ("footer.php"); 

if ($_SESSION['user_session']['link'] !="")
{
  $link = $_SESSION['user_session']['link'];
  echo "<script type=\"text/javascript\">
              window.location =\"$link\"
          </script>";
     $_SESSION['user_session']['link'] = "";
}

?>
</html>







