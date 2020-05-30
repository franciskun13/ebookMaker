<?php
include("header.php");
include("dbconfig(ebooks).php");
?>

<!-- Link Background Script -->
<script>
var my_ebook = document.getElementById("my_ebook");
	my_ebook.style.background = "#1b6059";
</script>

<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../css/my-ebook.css" rel="stylesheet" type="text/css" />

<body>

<div id="content">

	<div id="form">
		<center><h2>My eBooks</h2></center>

		<?php
			$user_id = $_SESSION['user_session']['user_id'];

			if (isset($_GET["page"])) 
			{
			 $page  = $_GET["page"];
			} 
			else 
			{ 
			 $page=1; 
			}; 
			$start_from = ($page-1) * $results_per_page;

			$sql = "SELECT * FROM ".$datatable." WHERE user_id = ".$user_id."
			ORDER BY ebook_title ASC LIMIT $start_from, ".$results_per_page;
			$rs_result = $conn_ebooks->query($sql);

			if ($rs_result->num_rows > 0)
			{
			    // output data of each row
			    while($row = $rs_result->fetch_assoc()) 
			    {
			    	$ebook_id = $row["ebook_id"];
			    	$ebook_identifier = $row["ebook_identifiere"];

		        echo "<div id=\"ebook_title\">

		        	  <div id=\"ebook_text\"> <h3>" . $row["ebook_title"]. "</h3>
		        	  <br>"."<b>Author:</b> " . $row["ebook_creator"]. "<br>".
		        	  "<b>Genre:</b> ". $row["ebook_subject"]. "
		        	  </div><br>

		        	  <div id=\"ebook_buttons\">

		        	  <a style=\"color:black\" name=\"edit_next\" id=\"edit_next\" class=\"btn\" href=\"edit-ebook-info.php?id=$ebook_id\">Edit eBook Information</a>

		        	  <a style=\"color:black\" name=\"edit_next\" id=\"edit_next\" class=\"btn\" href=\"edit-content.php?id=$ebook_id\">Edit Content</a>

		        	  <div id=\"download-button\">
			              <a class=\"btn btn-success\">Download File</a>
				              <div id=\"download-content\">
							    <a href=\"download-epub.php?l=1&as=zip&id=$ebook_id\">As ZIP</a>
							    <a href=\"download-epub.php?as=epub&id=$ebook_id\">As EPUB</a>
		              		  </div>
		              </div>
		              <a name=\"delete\" id=\"\" class=\"btn btn-danger\" href=\"delete-ebook.php?id=$ebook_id\" onClick=\"return confirm('Are you sure you want to delete this eBook? (This cannot be undone.)')\">Delete</a>
		        	  </div>

		              </div>"; 
			    }
			    $sql = "SELECT COUNT(ebook_id) AS total FROM ".$datatable." WHERE user_id = ".$user_id;
				$result = $conn_ebooks->query($sql);
				$row = $result->fetch_assoc();
				$total_pages = ceil($row["total"] / $results_per_page); 
				// calculate total pages with results	
				echo "<div id=\"pagination\">";
				for ($i=1; $i<=$total_pages; $i++) 
				{  
				// print links for all pages
				$link_id = "#".$page;	
				$link_url = "my-ebook.php?page=".$i;
	            echo "<a id=\"$i\" href='".$link_url."'";
	            if ($i==$page)  echo "class='curPage'";
	            echo ">".$i."</a> ";       
				};
				echo "</div>"; 			  				
			 } 
			else
			{
			    echo "<div id=\"ebook_title\">
			    		<p><b>No eBook Found. </b>If you want to create a new one, 
			    		<a href=\"create-new.php\">click here.</a></p>
			    	  </div>

			    	  <div id=\"ebook_actions\">
			    	  </div>";
			}

			$conn_ebooks->close();

		?>

	</div><!-- end of 'form' -->
</div><!-- end of 'content' -->

</body>

<!-- Pagination Script -->
<script>
var page = <?php echo $page;?>;
var id = document.getElementById(page);
 id.style.textDecoration ="underline";
 id.style.fontWeight ="bold";
 id.style.fontSize ="16";
</script>

<?php
include("footer.php");

if ($_SESSION['user_session']['link'] !="")
{
	$link = $_SESSION['user_session']['link'];
	echo "<script type=\"text/javascript\">
              window.location =\"$link\"
          </script>";
     $_SESSION['user_session']['link'] = "";
}


?>




