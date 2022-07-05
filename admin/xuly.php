<?php

const file_folder = "../assets/upload_images/";

function upAndsave($file){  
    if (!file_exists(file_folder)) {
        mkdir(file_folder, 0777, true);
    }
    if($file){
        $target_file = file_folder.basename($file["name"]);
        return (move_uploaded_file($file["tmp_name"],$target_file));
    }
    else
    return false;
}
if(isset($_REQUEST['ok'])){
    $action = isset($_GET['action'])?$_GET['action']:""; 
    $type = isset($_POST['type'])?$_POST['type']:"";
    $name = isset($_POST['name'])?$_POST['name']:"";
    $value = isset($_POST['value'])?$_POST['value']:"";
    $selector = isset($_POST['selector'])?$_POST['selector']:"";
    $id = isset($_POST['ID'])?$_POST['ID']:0;
    $callback = isset($_POST['callback'])?$_POST['callback']:false;
    $file_anh = isset($_FILES['file_anh'])?$_FILES['file_anh']:false;


    if($action!=""){
        require_once '../App.php';
        $App = new App();

        if($file_anh && $file_anh["error"] != 4 && $file_anh["size"]>0){
            $value = "./assets/upload_images/".$_FILES['file_anh']['name'];
        }
        
        if(strtolower($type)=="js" || strtolower($type)=="css" ){
            $value = base64_encode($value);
        }
        
        if($action== "add"){
            $s = "INSERT INTO `config`(`Name`, `Value`, `Selector`, `Type`) 
            VALUES ('$name','$value','$selector','$type')";
            $run = $App->Insert($s)>0;

            if(!$run){
                $msg = "Lỗi! ".$App->GetConnection()->error;
            }
            else
            {
                upAndsave($file_anh);
            }

            if($callback){
                header("Location: $callback&msg=$msg");
            }
            else
            {
                echo $run;
            }
        }
        else
        if($action == "edit"){    
            $s = "UPDATE `config` SET 
            `Name`='$name',`Value`='$value',`Selector`='$selector',`Type`='$type' 
            WHERE `ID`='$id'";
            $run = $App->Update($s);
            if(!$run){
                $msg = "Lỗi cập nhật! ".$App->GetConnection()->error;
            }
            else
            {
                upAndsave($file_anh);
            }

            if($callback){
                    header("Location: $callback&msg=$msg");
            }
            else
            {
                    echo $run;
            }         
        }
        else
        {
            echo 0;
        }      
    }
    else
    echo 0;
    
}
else
if(isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET["oid"])){
    require_once '../App.php';
    $App = new App();
    $_id = $_GET['oid'];
    $s = "DELETE FROM `config` WHERE ID='$_id'";
    echo $run = $App->Delete($s);
}
else
if(isset($_REQUEST['action']) && $_REQUEST['action']=="change_pass"){
    $id = isset($_POST['id'])?$_POST['id']:$_SERVER['admin_id'];
    $u = isset($_POST['username']) ? $_POST['username'] :false;
    $p = isset($_POST['password'])?$_POST['password'] :false;
    require_once '../App.php';
    $App = new App();

    if($u && $p){       
        $s = "UPDATE `admin` 
        SET `Username`='$u',`Password`='$p' 
        WHERE `ID`='$id'";
        echo $run = $App->Update($s);       
    }
    else
    if($p){
        $s = "UPDATE `admin` 
        SET `Password`='$p' 
        WHERE `ID`='$id'";
        echo $run = $App->Update($s); 
    }
    else
    if($u){
        $s = "UPDATE `admin` 
        SET `Username`='$u'  
        WHERE `ID`='$id'";
        echo $run = $App->Update($s); 
    }
    else
    {
        echo 0;
    }
}
else
{
    echo 0;
}