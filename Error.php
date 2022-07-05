<link rel="stylesheet" href="./assets/css/Error_Style.css">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<title>ERORR</title>
<?php
$title = isset($_GET['title'])?$_GET['title']:"Error";
$msg = isset($_GET['msg'])?$_GET['msg']:"TRANG NÀY KHÔNG TỒN TẠI!";
$code = [4,0,4];
if(isset($_GET['code'])){
  if($_GET['code']==400){
    $code = [4,0,0];
  }
  else
  if($_GET['code']==500){
    $code = [5,0,0];
  }
  else
  {
    $code = [0,0,0];
  }
}
?>

<h1><?=$title;?></h1>
<p class="zoom-area"><b><?=$msg;?></b></p>
<section class="error-container">
  <span><?=$code[0];?></span>
  <span><span class="screen-reader-text"><?=$code[1];?></span></span>
  <span><?=$code[2];?></span>
</section>
<div class="link-container">
  <a href="./" class="more-link">Về trang chủ</a>
</div>