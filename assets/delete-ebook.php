<?php

include ('session.php');
include ('dbconfig(ebooks).php');

//getting id of the data from url

$id = $_GET['id'];

$result_ebook = $conn_ebooks->query("SELECT * FROM ebooks WHERE ebook_id=$id");
$row_ebook = $result_ebook->fetch_assoc();
$ebook_identifier = $row_ebook["ebook_identifier"];

$path ="html/".$ebook_identifier;

function removeDirectory($path) {
 	$files = glob($path . '/*');
	foreach ($files as $file) {
		is_dir($file) ? removeDirectory($file) : unlink($file);
	}
	rmdir($path);
 	return;
}

removeDirectory($path);

//deleting the ebook from database
$result = $conn_ebooks->query("DELETE FROM ebooks WHERE ebook_id=$id");
//deleting the page from specific ebook from database
$result_page = $conn_ebooks->query("DELETE FROM pages WHERE ebook_id=$id");

//redirecting to the display page (index.php in our case)

header("Location:my-ebook.php");
?>
	
	


