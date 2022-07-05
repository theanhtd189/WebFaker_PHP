<?php
session_start();
if(isset($_GET['url'])){
    include 'App.php';
    $App = new App();
    $url = $_GET['url'];
    $url = base64_decode($url);
    $_url = $_url = $App->startNode.$url;
    // echo $url.'<br>';
    // print_r(pathinfo($url));
    echo $_url;
    //header("Location: $_url");
}
else 
{
    $_SESSION['url'] = "";
    header('Location: Index');
}