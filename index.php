<?php
session_start();
$starttime = microtime(true);
include_once './App.php';

$App = new App();

// $ip = $_SERVER['REMOTE_ADDR']; 
// $App->getUniqueVisitorCount($ip);
// echo 'Unique visitor count: ' . $_SESSION['visitor_count'];

$App->RunThread();
?>

<html>

<head>
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="./assets/css/style.css">
</head>

<body>
  <?php
  if ($App->crawl_stt == TRUE) {
  ?>
    <!-- <div class="loading">
      <div class="birdLoader" id="loading">
        <span class="birdLoader__red"></span>
        <span class="birdLoader__white"></span>
        <span class="birdLoader__grey"></span>
        <span class="birdLoader__yellow"></span>
        <span class="birdLoader__orange"></span>
        <span class="birdLoader__blue"></span>
      </div>
    </div> -->
    <div id="ta1o9er_contents">
      <?php {
        $App->GetContent();
      }
      ?>
    </div>
  <?php {
      include_once 'ScriptJS.php';
    }
  }
  ?>
</body>
</html>
<?php
$endtime = microtime(true); 
//printf("Page loaded in %f seconds", $endtime - $starttime);
?>