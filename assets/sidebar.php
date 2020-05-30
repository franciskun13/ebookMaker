<style>

.panel {
  border: 1px solid #d0d0d0;
  width: 20vw;
  position: absolute;
  margin-top: 60px;
  margin-left: 10px;
  margin-right: 10px; 
  z-index: 1;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.content{
  margin: 10px 10px 10px 10px;
  font-size: 13px;
  display: block;

}

.title {
  font-family: sans-serif;
  padding: 8px 0 10px 12px;
  color: #ffffff;
  overflow: hidden;
  background: #34baac;
  font-weight: bold;
}

.left,
.right {
  position: absolute;
  top: 0;
  right: 0;
  font-size: 20px;
  font-weight: bold;
  letter-spacing: -6px;
  display: block;
  cursor: pointer;
  background: #34baac;
  color: #ffffff;
}

.left {
  padding: 5px 19px 5px 15px;
}

.right {
  padding: 5px 19px 5px 16px;
  display: none;
}

.left:after {
  content: '\2329\2329\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\2329\2329';
}

.right:after {
  content: '\232A\232A\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\00A0\232A\232A';
}

.left:hover,
.right:hover {
  color: #ffffff;
  background: #26897e;
}
</style>

<!-- <link href="../css/bootstrap.min.css" rel="stylesheet" type="text/css" /> -->
<script type="text/javascript" src="../js/bootstrap.min.js"></script>
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/jquery.min.js"></script>


<div class="panel">
  <div class="title">PAGES</div>
  <div class="left"></div>
  <div class="right"></div>
  <div class="content">
  
  <?php
  $counter = 1;
  $result_page = $conn_ebooks->query("SELECT * FROM pages WHERE ebook_id = $id  ORDER BY page_id");
  while($row_page = $result_page->fetch_assoc()) 
	{
	$new_count = $counter++;
	echo "<a id=\"a$new_count\" href=\"edit-content.php?id=".$row_page['ebook_id']."&page=$new_count\">Page $new_count - <b>".$row_page['page_name']."</b></a><br>";
	}

  ?>
  </div>
</div>



<script>

$(".left").click(function () {
  $(".panel").css("width","33px");
  $(".content").hide();
  $("#form-box").css("margin-right","180px");
  $("#form-box").css("margin-left","180px");
  $(".left").hide();
  $(".right").show();
});

$(".right").click(function () {
  $(".panel").css("width","20vw");
  $(".panel").css("margin-right","10px;");
  $("#form-box").css("margin-right","60px");
  $("#form-box").css("margin-left","300px");
  $(".content").show();
  $(".right").hide();
  $(".left").show();
});

</script>