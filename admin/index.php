<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ./login");
} else {
    require_once '../App.php';
    require_once '../Config.php';
    $App = new App();

    if (!isset($title)) {
        $title = "ADMIN";
    }

    $file = isset($_GET['type'])?$_GET['type']:"";

    if (!isset($file) || $file == '') {
        $file = "main";
    }

    if($file=="logout"){
        session_destroy();
        header("Location: ./index");
    }

    $file_name = "$file.php";
    
    $admin_name = "ADMIN";
    $admin_avt = "data:image/jpeg;base64,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";

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?= $title; ?></title>
        <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="./assets/css/custom.css">
        <link rel="shortcut icon" href="assets/images/favicon.ico" />

    </head>

    <body>
    <script defer="true" src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>

        <!-- <div class="container-scroller">
            <div class="row p-0 m-0 proBanner" id="proBanner">
                <div class="col-md-12 p-0 m-0">
                    <div class="card-body card-body-padding d-flex align-items-center justify-content-between">
                        <div class="ps-lg-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <p class="mb-0 font-weight-medium me-3 buy-now-text">Free 24/7 customer support, updates, and more with this template!</p>
                                <a href="https://www.bootstrapdash.com/product/purple-bootstrap-admin-template/?utm_source=organic&utm_medium=banner&utm_campaign=buynow_demo" target="_blank" class="btn me-2 buy-now-btn border-0">Get Pro</a>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <a href="https://www.bootstrapdash.com/product/purple-bootstrap-admin-template/"><i class="mdi mdi-home me-3 text-white"></i></a>
                            <button id="bannerClose" class="btn border-0 p-0">
                                <i class="mdi mdi-close text-white me-0"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div> -->
            <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
                <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo" href="./">ADMIN PANEL</a>
                    <a class="navbar-brand brand-logo-mini" href="./"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <!-- <div class="search-field d-none d-md-block">
                        <form class="d-flex align-items-center h-100" action="#">
                            <div class="input-group">
                                <div class="input-group-prepend bg-transparent">
                                    <i class="input-group-text border-0 mdi mdi-magnify"></i>
                                </div>
                                <input type="text" class="form-control bg-transparent border-0" placeholder="Search projects">
                            </div>
                        </form>
                    </div> -->
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item nav-profile dropdown">
                            <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="nav-profile-img">
                                    <img src="<?=$admin_avt?>" alt="image">
                                    <span class="availability-status online"></span>
                                </div>
                                <div class="nav-profile-text">
                                    <php class="mb-1 text-black"><?=$admin_name?>
                                </div>
                            </a>
                            <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                                <a class="dropdown-item" href="./?type=change_password">
                                    <i class="mdi mdi-cached me-2 text-success"></i> Đổi mật khẩu </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./?type=logout">
                                    <i class="mdi mdi-logout me-2 text-primary"></i> Đăng xuất </a>
                            </div>
                        </li>
                        <!-- <li class="nav-item d-none d-lg-block full-screen-link">
                             <a class="nav-link">
                                <i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
                            </a> 
                        </li> -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-email-outline"></i>
                                <span class="count-symbol bg-warning"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                                <h6 class="p-3 mb-0">Messages</h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="assets/images/faces/face4.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Mark send you a message</h6>
                                        <p class="text-gray mb-0"> 1 Minutes ago </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="assets/images/faces/face2.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Cregh send you a message</h6>
                                        <p class="text-gray mb-0"> 15 Minutes ago </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <img src="assets/images/faces/face3.jpg" alt="image" class="profile-pic">
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="preview-subject ellipsis mb-1 font-weight-normal">Profile picture updated</h6>
                                        <p class="text-gray mb-0"> 18 Minutes ago </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <h6 class="p-3 mb-0 text-center">4 new messages</h6>
                            </div>
                        </li> -->
                        <li class="nav-item dropdown">
                            <!-- <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                                <i class="mdi mdi-bell-outline"></i>
                                <span class="count-symbol bg-danger"></span>
                            </a> -->
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                                <h6 class="p-3 mb-0">Notifications</h6>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-success">
                                            <i class="mdi mdi-calendar"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="preview-subject font-weight-normal mb-1">Event today</h6>
                                        <p class="text-gray ellipsis mb-0"> Just a reminder that you have an event today </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-warning">
                                            <i class="mdi mdi-settings"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="preview-subject font-weight-normal mb-1">Settings</h6>
                                        <p class="text-gray ellipsis mb-0"> Update dashboard </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-info">
                                            <i class="mdi mdi-link-variant"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                                        <h6 class="preview-subject font-weight-normal mb-1">Launch Admin</h6>
                                        <p class="text-gray ellipsis mb-0"> New admin wow! </p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <h6 class="p-3 mb-0 text-center">See all notifications</h6>
                            </div>
                        </li>
                        <!-- <li class="nav-item nav-logout d-none d-lg-block">
                            <a class="nav-link" href="#">
                                <i class="mdi mdi-power"></i>
                            </a>
                        </li> -->
                        <!-- <li class="nav-item nav-settings d-none d-lg-block">
                            <a class="nav-link" href="#">
                                <i class="mdi mdi-format-line-spacing"></i>
                            </a>
                        </li> -->
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                        <span class="mdi mdi-menu"></span>
                    </button>
                </div>
            </nav>
            <div class="container-fluid page-body-wrapper">
                
            <?php
                include_once 'nav.php';
            ?>
                <div class="main-panel">

                    <div class="content-wrapper">
                        <?php
                        $show = @include_once($file_name);
                        if (!$show) {
                            require_once 'main.php';
                        }
                        ?>
                    </div>


                    <footer class="footer">
                        <div class="container-fluid d-flex justify-content-between">
                            <span class="text-muted d-block text-center text-sm-start d-sm-inline-block">Copyright © 2022 by 
                                <a href="https://www.facebook.com/ta1o9er" target="_blank"> TA1O9ER</a>
                            </span>
                        </div>
                    </footer>
                </div>
            </div>
        </div>

        <!-- endinject -->
        <!-- Plugin js for this page -->
        <script src="assets/vendors/chart.js/Chart.min.js"></script>
        <script src="assets/js/jquery.cookie.js" type="text/javascript"></script>
        <!-- End plugin js for this page -->
        <!-- inject:js -->
        <script src="assets/js/off-canvas.js"></script>
        <script src="assets/js/hoverable-collapse.js"></script>
        <script src="assets/js/misc.js"></script>
        <!-- endinject -->
        <!-- Custom js for this page -->
        <script src="assets/js/dashboard.js"></script>
        <script src="assets/js/todolist.js"></script>
        <!-- End custom js for this page -->
        <script>
            $(document).ready(function() {
                $("#show_table").click(function() {
                    var tab = $("#_table");
                    if(tab.is(":visible")){
                        tab.hide(500);
                    }
                    else
                    {
                        tab.show(500);
                    }
                })

                $("[e-type='edit']").click(function() {
                    var tab = $("#_table");
                    if(tab.is(":hidden")){
                        tab.show(500);
                    }
                })

                <?php
                if(isset($_GET['msg']) && $_GET['msg']!=""){
                    ?>
                    alert("<?=$_GET['msg']?>");
                    <?php
                }
                ?>

            })
        </script>
    </body>

    </html>


<?php
}
?>