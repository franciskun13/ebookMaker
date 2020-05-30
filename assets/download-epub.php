<?php
// This is only to make sure the charset is UTF-8
// You may remove this line.
header('Content-Type: text/html; charset=utf-8');

// The class is in the folder classes
require ('../classes/TPEpubCreator.php');
require ('session.php');
require ('dbconfig(ebooks).php');

// Here we go

$epub = new TPEpubCreator();

$download_type = $_GET['as'];
$ebook_id = $_GET['id'];
$link = $_GET['l'];
// echo $ebook_id;

//query for ebook
$result_ebook = $conn_ebooks->query("SELECT * FROM ebooks WHERE ebook_id=$ebook_id");
//query of all pages
$result_page = $conn_ebooks->query("SELECT * FROM pages WHERE ebook_id = $ebook_id  ORDER BY page_id ASC");

//rows from ebooks
$row_ebook = $result_ebook->fetch_assoc();

//image cover link
$img_cover_link = getcwd()."/html/".$row_ebook['ebook_identifier']."/images/".$row_ebook['ebook_cover_image'];

// Temp folder and epub file name (path)
$epub->temp_folder = '../temp_folder/';

if ($download_type == "epub")
{
$epub->epub_file = '../epubs/'.$row_ebook['ebook_identifier'].'.epub';
}
else
{
$epub->epub_file = '../epubs/'.$row_ebook['ebook_identifier'].'.zip';  
}
// E-book configs
$epub->title = $row_ebook['ebook_title'];
$epub->creator = $row_ebook['ebook_author'];
$epub->subject = $row_ebook['ebook_genre'];
$epub->identifier = $row_ebook['ebook_identifier'];
$epub->language = $row_ebook['ebook_language'];
$epub->rights = $row_ebook['ebook_rights'];

// You can specify your own CSS
$epub->css = file_get_contents('../css/base.css');

// $epub->uuid = '';  // You can specify your own uuid

// Add page from file (just the <body> content)
// You have to remove doctype, head and body tags
// Sintax: $epub->AddPage( XHTML, file, title, download images );

while($row_page = $result_page->fetch_assoc()) 
{

$page_content = $row_page['page_content'];
$page_name = $row_page['page_name'];

$old_br = "<br>";
$old_hr = "<hr>";
$new_br = "<br/>";
$new_hr = "<hr/>";

  $new_page_content = str_replace($old_br,$new_br, $page_content);
  $new_page_content = str_replace($old_hr,$new_hr, $new_page_content);

if ($page_content == '')
{
$epub->AddPage(' ', false, $page_name  );
}
else if ($page_content != '')
{
$epub->AddPage($new_page_content, false, $page_name  );
}

}

// Add page content directly (just the <body> content)
// You must not use doctype, head and body tags (only XHTML body content)
// $epub->AddPage( '<b>Test</b>', false, 'Title 2' );
// $epub->AddPage( '<img src="images/2.jpg" />', false, 'Title 3' );

// Here the last param tells the class to download de image
// $epub->AddPage( '<img src="images/3.jpg" />', false, 'Title 4', true );

// $epub->AddPage( '<img src="images/4.jpg" />', false, 'Title 5' );

// Add image cover
// Make sure only one image is set to cover (last argument = true).
// If more than one image is set to cover, readers would not load the e-book.
// Sintax: $epub->AddImage( image path, mimetype, cover );

if ($row_ebook['ebook_cover_image'] !="")
{
$epub->AddImage( $img_cover_link, false, true );
}
// Add another images (last arg is set to false - not cover - remember that)
// $epub->AddImage( 'images/2.jpg', 'image/jpeg', false );

// // If you don't send the mimetype, the class will try to get it from the file
// $epub->AddImage( 'images/4.jpg', false, false );

// Create the EPUB
// If there is some error, the epub file will not be created
if ( ! $epub->error ) {

    // Since this can generate new errors when creating a folder
    // We'll check again

    unlink("../epubs/".$epub->epub_file);

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
                    /* Source directory (can be an FTP address) */
                    $src = getcwd()."/html/".$epub->identifier;
                    /* Full path to the destination directory */
                    $dst = $epub->temp_folder . '/OEBPS/html/docimages';

                    /* Usage */
                    recurse_copy($src, $dst);


    $epub->CreateEPUB();
    
    // If there's no error here, you're e-book is successfully created
    if ( ! $epub->error ) {
        // echo 'Success: Download your book <a href="' . $epub->epub_file . '">here</a>.';

        $_SESSION['user_session']['link'] = $epub->epub_file;

        if ($link == 1)
        {
          echo "<script type=\"text/javascript\">
              window.location = \"my-ebook.php?id=$ebook_id\"
              </script>";
        }
        else{
          echo "<script type=\"text/javascript\">
              window.location = \"edit-content.php?id=$ebook_id\"
              </script>";
        }
        
        
    }
    
} else {
    // If for some reason you're e-book hasn't been created, you can see whats
    // going on
    echo $epub->error;  
}
