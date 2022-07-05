<?php
session_start();
require_once 'App.php';

class Api extends App
{
    public $json;

    function __construct()
    {
        parent::__construct();
        $this->json = new stdClass();
        $this->json->type = 'ERROR';
        $this->json->message = '';
        $this->json->data = null;
    }

    public function ReturnJSON($name = "", $obj = [])
    {
        if (gettype($obj) == 'object') {
            $a1 = (array)$obj;
            $a2 = (array)$this->json;
            $obj = array_merge($a1, $a2);
        }
        if ($name == "") {
            return json_encode(
                $obj,
                JSON_PRETTY_PRINT
            );
        } else {
            return json_encode(
                ["$name" => $obj],
                JSON_PRETTY_PRINT
            );
        }
    }

    public function GetJSON($table, $name = "", $c_name = "", $c_value = "")
    {
        $table = mysqli_real_escape_string($this->con, $table);
        if ($name != "") {
            $name = mysqli_real_escape_string($this->con, $name);
        } else {
            $name = "*";
        }
        
        if ($c_name != "") {
            $c_name = mysqli_real_escape_string($this->con, $c_name);
            if ($c_value != "") {
                $c_value = mysqli_real_escape_string($this->con, $c_value);
                $q  = "select $name from $table where $c_name='$c_value'";
            }
        } else {
            $q  = "select $name from $table";
        }

      //  echo "tab: ".$table." name: ".$name." c_name: ".$c_name." c_vl:".$c_value."<br>";
        
        $array = App::Select($q,0);

        $a = [];
        if($this->con->error){
            $this->json->message = $this->con->error;
            return $this->json;
        }
        else
        {
            while ($r = $array->fetch_assoc()) {
                array_push($a, $r);
            }
            $this->json->type = "OK";
            return ($a);
        }
        
    }

    public function LoginAdmin($username,$password){
        if (isset($username) && isset($password)) {
            $u = isset($username) ? mysqli_real_escape_string($this->GetConnection(), $username) : "";
            $p = isset($password) ? mysqli_real_escape_string($this->GetConnection(), $password) : "";
            $result = App::Select("SELECT * FROM `admin` WHERE Username='$u' and Password='$p'",1);
            if($result){                        
                if(count($result)<1){
                    return "0 result";
                }
                else
                {
                    $this->json->data = [
                        "ID"=>$result["ID"],
                        "Username"=>$result["Username"]
                    ];
                    $this->json->type = "OK";
                    $_SESSION['admin_id'] = $result["ID"];
                    $this->json->message = "Đăng nhập thành công!";
                }

            } else
            {
                $this->json->type = "ERROR";
                $this->json->message = "Tên đăng nhập hoặc mật khẩu không đúng!";
            }
        }
        else
        {
            $this->json->message = "Username hoặc Password không được trống!";
           
        }

        return $this->json;
    }
}

$api = new Api();
$result = $api->GetJSON("config","*");
if (isset($_GET['type']))
{
    $type = $_GET['type'];
    $result = [];
    if ($type != '') {   
        if($type='login'){
            if(isset($_GET['username']) && isset($_GET['password'])){
                $api->json->type = "ERROR";
                if($_GET['username']==""){             
                    $api->json->message = "Username không được để trống!";
                    $result = $api->json;
                }
                else
                if($_GET['password']=="") {
                    $api->json->message = "Password không được để trống!";
                    $result = $api->json;
                }
                else {
                    $get = $api->LoginAdmin($_GET['username'], $_GET['password']);
                    $result = $get;
                }               
            }
            else
            {
                $api->json->type = "ERROR";
                $api->json->message = "Tham số không hợp lệ!";
                $result = $api->json;
            }
        }   
        else
        {
            $result = $api->GetJSON("config","*","type",$type);    
        } 
    }   
}

print_r($api->ReturnJSON("", $result));
