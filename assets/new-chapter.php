<?php
include ("header.php");
?>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.css">

<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.0/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />

<link href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.6.0/css/froala_style.min.css" rel="stylesheet" type="text/css" />

<link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../css/new-chapter.css" rel="stylesheet" type="text/css" />


</head>

<body>

<div id="body">
<div id="form-box">

	
		<h4><b>Editing:</b></h4>
	<div id="buttons">
		<input type="submit" name="save" id="save" class="btn btn-primary" value="Save Changes">
		<a class="btn btn-success" href="">Download Epub File</a><br>
		<input type="submit" name="new" id="new" class="btn btn-primary" value="Create New Chapter">
		<input type="submit" name="edit_previous" id="edit_previous" class="btn" value="Edit Previous Chapter">
		<input type="submit" name="edit_next" id="edit_next" class="btn" value="Edit Next Chapter">
		Editing Chapter 1 of 1<br><br>
		<b>Chapter Name:</b><br>
		<input style="height: 32px; width: 100%;" type="text" class="input-xxlarge" 
		id="chapter" name="chapter" value="">

	</div> <!-- end of 'button' -->
	
	<div id="wysiwyg">
		<textarea id="froala-editor"></textarea>
	</div> <!-- end of 'wysiwyg' -->

	<div id="bottom">
		<input type="submit" name="save" id="delete" class="btn btn-danger" value="Delete Chapter">
		<p>Pages:</p>
	</div> <!-- end of 'bottom' -->

</div> <!-- end of 'form-box' -->

  </div> <!-- end of 'button' -->



<!-- Include external JS libs. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/codemirror.min.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.25.0/mode/xml/xml.min.js"></script>

<!-- Include Editor JS files. -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.3/js/froala_editor.pkgd.min.js"></script>

<script type="text/javascript" src="../js/file.min.js"></script>

<script type="text/javascript" src="../js/image.min.js"></script>

<script>
$(function() {
  $('textarea#froala-editor').froalaEditor({

  	 heightMin: 800,
     heightMax: 1300,

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

<script>
  $(function() {
    $('.selector')
      .froalaEditor({
        // Set the image upload parameter.
        imageUploadParam: 'file',
        
        // Set the image upload URL.
        imageUploadURL: "http://localhost/eBookMaker/assets/upload-image.php",

        // Additional upload params.
        imageUploadParams: {id: 'my_editor'},

         // Set the file upload parameter.
        fileUploadParam: 'file',
 
        // Set the file upload URL.
        fileUploadURL: "http://localhost/eBookMaker/assets/upload-image.php",
 
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

</body>
</html>


<?php
include("footer.php");
?>


